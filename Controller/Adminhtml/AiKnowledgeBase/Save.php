<?php

namespace Gtstudio\AiKnowledgeBase\Controller\Adminhtml\AiKnowledgeBase;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterfaceFactory;
use Gtstudio\AiKnowledgeBase\Api\SaveAiKnowledgeBaseInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Save AiKnowledgeBase controller action.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Gtstudio_AiKnowledgeBase::management';

    /**
     * @var DataPersistorInterface
     */
    private DataPersistorInterface $dataPersistor;

    /**
     * @var SaveAiKnowledgeBaseInterface
     */
    private SaveAiKnowledgeBaseInterface $saveCommand;

    /**
     * @var AiKnowledgeBaseInterfaceFactory
     */
    private AiKnowledgeBaseInterfaceFactory $entityDataFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param SaveAiKnowledgeBaseInterface $saveCommand
     * @param AiKnowledgeBaseInterfaceFactory $entityDataFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        SaveAiKnowledgeBaseInterface $saveCommand,
        AiKnowledgeBaseInterfaceFactory $entityDataFactory
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->saveCommand = $saveCommand;
        $this->entityDataFactory = $entityDataFactory;
    }

    /**
     * Save AiKnowledgeBase Action.
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            $generalData = $params['general'] ?? [];

            // Multiselect returns an array; store as comma-separated string.
            if (isset($generalData['agent_ids']) && is_array($generalData['agent_ids'])) {
                $generalData['agent_ids'] = implode(',', array_filter($generalData['agent_ids']));
            }

            /** @var AiKnowledgeBaseInterface|DataObject $entityModel */
            $entityModel = $this->entityDataFactory->create();
            $entityModel->addData($generalData);
            $this->saveCommand->execute($entityModel);
            $this->messageManager->addSuccessMessage(
                __('The AiKnowledgeBase data was saved successfully')
            );
            $this->dataPersistor->clear('entity');
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $this->dataPersistor->set('entity', $params);

            return $resultRedirect->setPath('*/*/edit', [
                AiKnowledgeBaseInterface::ENTITY_ID => $this->getRequest()->getParam(
                    AiKnowledgeBaseInterface::ENTITY_ID
                )
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
