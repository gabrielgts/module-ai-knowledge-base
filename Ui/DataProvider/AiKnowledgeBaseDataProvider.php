<?php

namespace Gtstudio\AiKnowledgeBase\Ui\DataProvider;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Gtstudio\AiKnowledgeBase\Api\GetAiKnowledgeBaseListInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\SearchResultFactory;

/**
 * DataProvider component.
 */
class AiKnowledgeBaseDataProvider extends DataProvider
{
    /**
     * @var GetAiKnowledgeBaseListInterface
     */
    private GetAiKnowledgeBaseListInterface $getListQuery;

    /**
     * @var SearchResultFactory
     */
    private SearchResultFactory $searchResultFactory;

    /**
     * @var array
     */
    private $loadedData = [];

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param GetAiKnowledgeBaseListInterface $getListQuery
     * @param SearchResultFactory $searchResultFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        GetAiKnowledgeBaseListInterface $getListQuery,
        SearchResultFactory $searchResultFactory,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->getListQuery = $getListQuery;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * Returns searching result.
     *
     * @return SearchResultFactory
     */
    public function getSearchResult()
    {
        $searchCriteria = $this->getSearchCriteria();
        $result = $this->getListQuery->execute($searchCriteria);

        return $this->searchResultFactory->create(
            $result->getItems(),
            $result->getTotalCount(),
            $searchCriteria,
            AiKnowledgeBaseInterface::ENTITY_ID
        );
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData(): array
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }
        $this->loadedData = parent::getData();
        $itemsById = [];

        foreach ($this->loadedData['items'] as $item) {
            $itemsById[(int)$item[AiKnowledgeBaseInterface::ENTITY_ID]] = $item;
        }

        if ($id = $this->request->getParam(AiKnowledgeBaseInterface::ENTITY_ID)) {
            $item = $itemsById[(int)$id] ?? [];
            // Convert stored comma string to array so the multiselect renders correctly.
            if (!empty($item[AiKnowledgeBaseInterface::AGENT_IDS])) {
                $item[AiKnowledgeBaseInterface::AGENT_IDS] = array_map(
                    'intval',
                    explode(',', (string)$item[AiKnowledgeBaseInterface::AGENT_IDS])
                );
            }
            $this->loadedData['entity'] = $item;
        }

        return $this->loadedData;
    }
}
