<?php

namespace Gtstudio\AiKnowledgeBase\Mapper;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterfaceFactory;
use Gtstudio\AiKnowledgeBase\Model\AiKnowledgeBaseModel;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Converts a collection of AiKnowledgeBase entities to an array of data transfer objects.
 */
class AiKnowledgeBaseDataMapper
{
    /**
     * @var AiKnowledgeBaseInterfaceFactory
     */
    private AiKnowledgeBaseInterfaceFactory $entityDtoFactory;

    /**
     * @param AiKnowledgeBaseInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        AiKnowledgeBaseInterfaceFactory $entityDtoFactory
    ) {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|AiKnowledgeBaseInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var AiKnowledgeBaseModel $item */
        foreach ($collection->getItems() as $item) {
            /** @var AiKnowledgeBaseInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
