<?php

namespace Gtstudio\AiKnowledgeBase\Model;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * AiKnowledgeBase entity search results implementation.
 */
class AiKnowledgeBaseSearchResults extends SearchResults implements AiKnowledgeBaseSearchResultsInterface
{
    /**
     * Set items list.
     *
     * @param array $items
     *
     * @return AiKnowledgeBaseSearchResultsInterface
     */
    public function setItems(array $items): AiKnowledgeBaseSearchResultsInterface
    {
        return parent::setItems($items);
    }

    /**
     * Get items list.
     *
     * @return array
     */
    public function getItems(): array
    {
        return parent::getItems();
    }
}
