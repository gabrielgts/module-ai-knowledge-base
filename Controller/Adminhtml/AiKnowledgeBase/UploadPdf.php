<?php

declare(strict_types=1);

namespace Gtstudio\AiKnowledgeBase\Controller\Adminhtml\AiKnowledgeBase;

use Gtstudio\AiKnowledgeBase\Model\Service\PdfParserService;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * Upload a PDF document and return its extracted text and metadata as JSON.
 *
 */
class UploadPdf extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Gtstudio_AiKnowledgeBase::management';

    private const ALLOWED_EXTENSIONS = ['pdf'];
    private const MAX_FILE_SIZE = 52428800; // 50 MB
    private const TMP_SUBDIR = 'pdf_uploads';

    /**
     * @param Action\Context $context
     * @param JsonFactory $resultJsonFactory
     * @param PdfParserService $pdfParser
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(
        Action\Context $context,
        private readonly JsonFactory $resultJsonFactory,
        private readonly PdfParserService $pdfParser,
        private readonly Filesystem $filesystem,
        private readonly UploaderFactory $uploaderFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Execute upload action.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            $uploader = $this->resolveUploader();
            $uploader->setAllowedExtensions(self::ALLOWED_EXTENSIONS);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);

            $tmpWrite = $this->filesystem->getDirectoryWrite(DirectoryList::TMP);
            $tmpPath  = $tmpWrite->getAbsolutePath(self::TMP_SUBDIR);

            if (!$tmpWrite->isExist(self::TMP_SUBDIR)) {
                $tmpWrite->create(self::TMP_SUBDIR);
            }

            $saved    = $uploader->save($tmpPath);
            $filePath = $saved['path'] . DIRECTORY_SEPARATOR . $saved['file'];

            $stat = $tmpWrite->stat(self::TMP_SUBDIR . DIRECTORY_SEPARATOR . $saved['file']);
            if (($stat['size'] ?? 0) > self::MAX_FILE_SIZE) {
                $tmpWrite->delete(self::TMP_SUBDIR . DIRECTORY_SEPARATOR . $saved['file']);
                throw new LocalizedException(__('File size exceeds the 50 MB limit.'));
            }

            ['text' => $text, 'metadata' => $metadata] = $this->pdfParser->parse($filePath);

            $tmpWrite->delete(self::TMP_SUBDIR . DIRECTORY_SEPARATOR . $saved['file']);

            return $resultJson->setData([
                'success'  => true,
                'file'     => $saved['file'],
                'name'     => $saved['name'],
                'content'  => $text,
                'title'    => $metadata['title'] ?: $this->stemFilename((string)$saved['name']),
                'tags'     => implode(', ', array_filter([$metadata['subject'], $metadata['keywords']])),
                'metadata' => $metadata,
            ]);
        } catch (LocalizedException $e) {
            return $resultJson->setData(['success' => false, 'error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return $resultJson->setData([
                'success' => false,
                'error'   => 'An error occurred while processing the file: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Resolve the fileUploader component.
     *
     *
     * @return \Magento\MediaStorage\Model\File\Uploader
     * @throws LocalizedException
     */
    private function resolveUploader(): \Magento\MediaStorage\Model\File\Uploader
    {
        /** @var HttpRequest $request */
        $request    = $this->getRequest();
        $filesArray = $request->getFiles()->toArray();

        $group  = $filesArray['general'] ?? $filesArray;
        $prefix = isset($filesArray['general']) ? 'general[' : '';

        $candidates = array_unique(array_merge(
            ['pdf_file', 'file', 'qqfile'],
            array_keys($group)
        ));

        foreach ($candidates as $fileId) {
            if (empty($group[$fileId]['name'])) {
                continue;
            }

            $uploaderKey = $prefix ? $prefix . $fileId . ']' : $fileId;
            try {
                return $this->uploaderFactory->create(['fileId' => $uploaderKey]);
            } catch (\Exception) {
                continue;
            }
        }

        throw new LocalizedException(__('No PDF file was found in the upload request.'));
    }

    /**
     * Return a filename without its extension.
     *
     * @param string $filename
     * @return string
     */
    private function stemFilename(string $filename): string
    {
        $dot = strrpos($filename, '.');
        return $dot !== false ? substr($filename, 0, $dot) : $filename;
    }
}
