<?php

namespace Gtstudio\AiKnowledgeBase\Query\AiKnowledgeBase;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseSearchResultsInterface;
use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseSearchResultsInterfaceFactory;
use Gtstudio\AiKnowledgeBase\Api\GetAiKnowledgeBaseListInterface;
use Gtstudio\AiKnowledgeBase\Mapper\AiKnowledgeBaseDataMapper;
use Gtstudio\AiKnowledgeBase\Model\ResourceModel\AiKnowledgeBaseModel\AiKnowledgeBaseCollection;
use Gtstudio\AiKnowledgeBase\Model\ResourceModel\AiKnowledgeBaseModel\AiKnowledgeBaseCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * Get AiKnowledgeBase list by search criteria query.
 */
class GetListQuery implements GetAiKnowledgeBaseListInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var AiKnowledgeBaseCollectionFactory
     */
    private AiKnowledgeBaseCollectionFactory $entityCollectionFactory;

    /**
     * @var AiKnowledgeBaseDataMapper
     */
    private AiKnowledgeBaseDataMapper $entityDataMapper;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var AiKnowledgeBaseSearchResultsInterfaceFactory
     */
    private AiKnowledgeBaseSearchResultsInterfaceFactory $searchResultFactory;

    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param AiKnowledgeBaseCollectionFactory $entityCollectionFactory
     * @param AiKnowledgeBaseDataMapper $entityDataMapper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AiKnowledgeBaseSearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        CollectionProcessorInterface                 $collectionProcessor,
        AiKnowledgeBaseCollectionFactory             $entityCollectionFactory,
        AiKnowledgeBaseDataMapper                    $entityDataMapper,
        SearchCriteriaBuilder                        $searchCriteriaBuilder,
        AiKnowledgeBaseSearchResultsInterfaceFactory $searchResultFactory
    )
    {
        $this->collectionProcessor = $collectionProcessor;
        $this->entityCollectionFactory = $entityCollectionFactory;
        $this->entityDataMapper = $entityDataMapper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): AiKnowledgeBaseSearchResultsInterface
    {
        /** @var AiKnowledgeBaseCollection $collection */
        $collection = $this->entityCollectionFactory->create();

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $entityDataObjects = $this->entityDataMapper->map($collection);

        /** @var AiKnowledgeBaseSearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems($entityDataObjects);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
