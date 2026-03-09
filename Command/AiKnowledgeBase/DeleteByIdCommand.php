<?php

namespace Gtstudio\AiKnowledgeBase\Command\AiKnowledgeBase;

use Exception;
use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Gtstudio\AiKnowledgeBase\Api\DeleteAiKnowledgeBaseByIdInterface;
use Gtstudio\AiKnowledgeBase\Model\AiKnowledgeBaseModel;
use Gtstudio\AiKnowledgeBase\Model\AiKnowledgeBaseModelFactory;
use Gtstudio\AiKnowledgeBase\Model\ResourceModel\AiKnowledgeBaseResource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Delete AiKnowledgeBase by id Command.
 */
class DeleteByIdCommand implements DeleteAiKnowledgeBaseByIdInterface
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
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * @inheritDoc
     */
    public function execute(int $entityId): void
    {
        try {
            /** @var AiKnowledgeBaseModel $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, AiKnowledgeBaseInterface::ENTITY_ID);

            if (!$model->getData(AiKnowledgeBaseInterface::ENTITY_ID)) {
                throw new NoSuchEntityException(
                    __('Could not find AiKnowledgeBase with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete AiKnowledgeBase. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete AiKnowledgeBase.'));
        }
    }
}
