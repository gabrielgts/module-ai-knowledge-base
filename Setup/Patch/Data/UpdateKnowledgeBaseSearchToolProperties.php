<?php

declare(strict_types=1);

namespace Gtstudio\AiKnowledgeBase\Setup\Patch\Data;

use Gtstudio\AiAgents\Model\ResourceModel\AiToolsResource;
use Gtstudio\AiAgents\Model\ResourceModel\AiToolsModel\AiToolsCollection;
use Gtstudio\AiAgents\Model\ResourceModel\AiToolsModel\AiToolsCollectionFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Ensures the knowledge_base_search tool has the correct properties JSON.
 *
 * Runs after CreateKnowledgeBaseSearchTool so that existing installations
 * where the tool was created without a property schema are updated automatically.
 */
class UpdateKnowledgeBaseSearchToolProperties implements DataPatchInterface
{
    private const TOOL_CODE = 'knowledge_base_search';

    private const TOOL_PROPERTIES = '[
        {
            "name": "search_term",
            "type": "string",
            "description": "The keyword or phrase to search for in the knowledge base",
            "required": true
        }
    ]';

    private const TOOL_DESCRIPTION =
        'Search the knowledge base for documents and information relevant to a query. '
        . 'Use this tool when you need to retrieve stored company knowledge, documentation, or FAQs.';

    /**
     * @param AiToolsCollectionFactory $toolsCollectionFactory
     * @param AiToolsResource $toolsResource
     */
    public function __construct(
        private readonly AiToolsCollectionFactory $toolsCollectionFactory,
        private readonly AiToolsResource $toolsResource
    ) {
    }

    /**
     * @inheritDoc
     */
    public function apply(): self
    {
        /** @var AiToolsCollection $collection */
        $collection = $this->toolsCollectionFactory->create();
        $collection->addFieldToFilter('code', self::TOOL_CODE);

        $tool = $collection->getFirstItem();

        if (!$tool->getId()) {
            return $this;
        }

        $tool->setData('description', self::TOOL_DESCRIPTION);
        $tool->setData('properties', self::TOOL_PROPERTIES);

        $this->toolsResource->save($tool);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [CreateKnowledgeBaseSearchTool::class];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
