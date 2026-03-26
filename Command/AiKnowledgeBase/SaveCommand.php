<?php

namespace Gtstudio\AiKnowledgeBase\Command\AiKnowledgeBase;

use Exception;
use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Gtstudio\AiKnowledgeBase\Api\SaveAiKnowledgeBaseInterface;
use Gtstudio\AiKnowledgeBase\Model\AiKnowledgeBaseModel;
use Gtstudio\AiKnowledgeBase\Model\AiKnowledgeBaseModelFactory;
use Gtstudio\AiKnowledgeBase\Model\ResourceModel\AiKnowledgeBaseResource;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;

/**
 * Save AiKnowledgeBase Command.
 */
class SaveCommand implements SaveAiKnowledgeBaseInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var AiKnowledgeBaseModelFactory
     */
    private AiKnowledgeBaseModelFactory $modelFactory;

    /**
     * @var AiKnowledgeBaseResource
     */
    private AiKnowledgeBaseResource $resource;

    /**
     * @param LoggerInterface $logger
     * @param AiKnowledgeBaseModelFactory $modelFactory
     * @param AiKnowledgeBaseResource $resource
     */
    public function __construct(
        LoggerInterface $logger,
        AiKnowledgeBaseModelFactory $modelFactory,
        AiKnowledgeBaseResource $resource
    ) {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * @inheritDoc
     */
    public function execute(AiKnowledgeBaseInterface $aiKnowledgeBase): int
    {
        try {
            /** @var AiKnowledgeBaseModel $model */
            $model = $this->modelFactory->create();
            $model->addData($aiKnowledgeBase->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(AiKnowledgeBaseInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save AiKnowledgeBase. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save AiKnowledgeBase.'));
        }

        return (int)$model->getData(AiKnowledgeBaseInterface::ENTITY_ID);
    }
}
