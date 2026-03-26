<?php

namespace Gtstudio\AiKnowledgeBase\Api;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Get AiKnowledgeBase list by search criteria query.
 *
 * @api
 */
interface GetAiKnowledgeBaseListInterface
{
    /**
     * Get AiKnowledgeBase list by search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseSearchResultsInterface
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): AiKnowledgeBaseSearchResultsInterface;
}
