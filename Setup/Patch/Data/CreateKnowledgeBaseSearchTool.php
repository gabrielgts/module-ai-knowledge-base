<?php

declare(strict_types=1);

namespace Gtstudio\AiKnowledgeBase\Setup\Patch\Data;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Gtstudio\AiAgents\Api\Data\AiToolsInterfaceFactory;
use Gtstudio\AiAgents\Api\SaveAiToolsInterface;
use Gtstudio\AiAgents\Model\ResourceModel\AiToolsModel\AiToolsCollectionFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Creates the knowledge_base_search tool record used by AiAgents.
 *
 * The tool is registered automatically on setup:upgrade so that any agent
 * can reference it without manual admin configuration.
 */
class CreateKnowledgeBaseSearchTool implements DataPatchInterface
{
    private const TOOL_CODE = 'knowledge_base_search';

    /**
     * JSON schema of the parameters this tool accepts.
     * Consumed by ToolPropertyMapper to build the LLM's tool-call schema.
     */
    private const TOOL_PROPERTIES = '[
        {
            "name": "search_term",
            "type": "string",
            "description": "The keyword or phrase to search for in the knowledge base",
            "required": true
        }
    ]';

    /**
     * @param SaveAiToolsInterface $saveTool
     * @param AiToolsCollectionFactory $toolsCollectionFactory
     * @param AiToolsInterfaceFactory $toolsDataFactory
     */
    public function __construct(
        private readonly SaveAiToolsInterface $saveTool,
        private readonly AiToolsCollectionFactory $toolsCollectionFactory,
        private readonly AiToolsInterfaceFactory $toolsDataFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function apply(): self
    {
        if ($this->toolExists()) {
            return $this;
        }

        /** @var AiToolsInterface $tool */
        $tool = $this->toolsDataFactory->create();
        $tool->setCode(self::TOOL_CODE);
        $tool->setDescription(
            'Search the knowledge base for documents and information relevant to a query. '
            . 'Use this tool when you need to retrieve stored company knowledge, documentation, or FAQs.'
        );
        $tool->setProperties(self::TOOL_PROPERTIES);

        $this->saveTool->execute($tool);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * Check whether the tool record already exists.
     *
     * @return bool
     */
    private function toolExists(): bool
    {
        $collection = $this->toolsCollectionFactory->create();
        $collection->addFieldToFilter('code', self::TOOL_CODE);
        return $collection->getSize() > 0;
    }
}
