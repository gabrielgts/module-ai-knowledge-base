<?php

declare(strict_types=1);

namespace Gtstudio\AiKnowledgeBase\Model\Tool;

use Gtstudio\AiAgents\Api\ToolExecutorInterface;
use Gtstudio\AiKnowledgeBase\Model\ResourceModel\AiKnowledgeBaseModel\AiKnowledgeBaseCollection;
use Gtstudio\AiKnowledgeBase\Model\ResourceModel\AiKnowledgeBaseModel\AiKnowledgeBaseCollectionFactory;

/**
 * Searches the knowledge base for entries relevant to a query.
 *
 * Registered as the executor for the `knowledge_base_search` tool.
 * Receives named parameters as keyed by the tool's property schema —
 * specifically `search_term` (string, required).
 */
class AiKnowledgeBaseSearchExecutor implements ToolExecutorInterface
{
    private const MAX_RESULTS       = 5;
    private const EXCERPT_LENGTH    = 400;

    /**
     * @param AiKnowledgeBaseCollectionFactory $collectionFactory
     */
    public function __construct(
        private readonly AiKnowledgeBaseCollectionFactory $collectionFactory
    ) {
    }

    /**
     * Search active knowledge base entries for the given term.
     *
     * @param array $inputs Named parameters from the LLM; expects 'search_term'.
     * @return string Formatted search results or a not-found message.
     */
    public function execute(array $inputs): mixed
    {
        $searchTerm = trim((string)($inputs['search_term'] ?? ''));

        if ($searchTerm === '') {
            return 'No search term provided.';
        }

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('is_active', 1);
        $collection->setPageSize(self::MAX_RESULTS);

        try {
            $this->applyFulltextSearch($collection, $searchTerm);
        } catch (\Exception) {
            $this->applyLikeSearch($collection, $searchTerm);
        }

        $results = [];
        foreach ($collection->getItems() as $item) {
            $excerpt = substr((string)$item->getData('content'), 0, self::EXCERPT_LENGTH);
            $results[] = sprintf(
                "Title: %s\nContent: %s\nTags: %s",
                $item->getData('title'),
                $excerpt . (strlen((string)$item->getData('content')) > self::EXCERPT_LENGTH ? '…' : ''),
                $item->getData('tags')
            );
        }

        if (empty($results)) {
            return 'No matching knowledge base entries found.';
        }

        return 'Found ' . count($results) . " result(s):\n\n" . implode("\n\n---\n\n", $results);
    }

    /**
     * Apply FULLTEXT boolean search.
     *
     * @param AiKnowledgeBaseCollection $collection
     * @param string $searchTerm
     * @return void
     */
    private function applyFulltextSearch(AiKnowledgeBaseCollection $collection, string $searchTerm): void
    {
        $connection = $collection->getConnection();
        $formatted  = '+' . str_replace(' ', ' +', $searchTerm) . '*';

        $collection->getSelect()->where(
            $connection->quoteInto(
                'MATCH(title, content, tags) AGAINST(? IN BOOLEAN MODE)',
                $formatted
            )
        );
    }

    /**
     * Apply LIKE search as fallback when FULLTEXT is unavailable.
     *
     * @param AiKnowledgeBaseCollection $collection
     * @param string $searchTerm
     * @return void
     */
    private function applyLikeSearch(AiKnowledgeBaseCollection $collection, string $searchTerm): void
    {
        $connection = $collection->getConnection();
        $like       = '%' . $connection->escapeLikeWildcards($searchTerm) . '%';

        $collection->addFieldToFilter(
            ['title', 'content', 'tags'],
            [['like' => $like], ['like' => $like], ['like' => $like]]
        );
    }
}
