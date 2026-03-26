<?php

declare(strict_types=1);

namespace Gtstudio\AiKnowledgeBase\Model\Service;

use Magento\Framework\Exception\LocalizedException;
use Smalot\PdfParser\Parser;

/**
 * PDF document parser service.
 *
 * Wraps smalot/pdfparser to extract plain text and metadata from PDF files.
 * Always parse via parse() to avoid reading the file twice.
 */
class PdfParserService
{
    private const MAX_FILE_SIZE = 52428800; // 50 MB

    /** @var Parser */
    private Parser $parser;

    /**
     * Initialize the PDF parser instance.
     */
    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * Parse a PDF file and return both extracted text and document metadata.
     *
     * @param string $filePath Absolute path to the PDF file.
     * @return array
     * @throws LocalizedException
     */
    public function parse(string $filePath): array
    {
        // phpcs:ignore Magento2.Functions.DiscouragedFunction.Discouraged
        if (!file_exists($filePath)) {
            throw new LocalizedException(__('File not found: %1', $filePath));
        }

        // phpcs:ignore Magento2.Functions.DiscouragedFunction.Discouraged
        if (filesize($filePath) > self::MAX_FILE_SIZE) {
            throw new LocalizedException(__('File size exceeds the 50 MB limit.'));
        }

        try {
            $pdf     = $this->parser->parseFile($filePath);
            $details = $pdf->getDetails();

            return [
                'text'     => $this->cleanText($pdf->getText()),
                'metadata' => [
                    'title'    => $details['Title']    ?? '',
                    'subject'  => $details['Subject']  ?? '',
                    'keywords' => $details['Keywords'] ?? '',
                    'author'   => $details['Author']   ?? '',
                    'creator'  => $details['Creator']  ?? '',
                    'pages'    => count($pdf->getPages()),
                ],
            ];
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new LocalizedException(__('Failed to parse PDF: %1', $e->getMessage()));
        }
    }

    /**
     * Extract plain text from a PDF file.
     *
     * @param string $filePath
     * @return string
     * @throws LocalizedException
     */
    public function extractText(string $filePath): string
    {
        return $this->parse($filePath)['text'];
    }

    /**
     * Extract metadata from a PDF file.
     *
     * @param string $filePath
     * @return array
     * @throws LocalizedException
     */
    public function extractMetadata(string $filePath): array
    {
        return $this->parse($filePath)['metadata'];
    }

    /**
     * Normalise extracted PDF text.
     *
     * @param string $text
     * @return string
     */
    private function cleanText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', $text);
        // phpcs:ignore Magento2.Functions.DiscouragedFunction.Discouraged
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        return trim($text);
    }
}
