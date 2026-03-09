<?php

namespace Gtstudio\AiKnowledgeBase\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * AiKnowledgeBase entity search result.
 */
interface AiKnowledgeBaseSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Set items.
     *
     * @param \Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface[] $items
     *
     * @return AiKnowledgeBaseSearchResultsInterface
     */
    public function setItems(array $items): AiKnowledgeBaseSearchResultsInterface;

    /**
     * Get items.
     *
     * @return \Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface[]
     */
    public function getItems(): array;
}
