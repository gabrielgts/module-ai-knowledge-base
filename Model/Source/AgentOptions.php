<?php

declare(strict_types=1);

namespace Gtstudio\AiKnowledgeBase\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\ResourceConnection;

/**
 * Provides a list of available AI agents as select options.
 *
 * Used by the knowledge base form to let the admin associate a document
 * with one or more agents that can use it for context retrieval.
 */
class AgentOptions implements OptionSourceInterface
{
    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        private readonly ResourceConnection $resourceConnection
    ) {
    }

    /**
     * Return all agents as option array.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $connection = $this->resourceConnection->getConnection();
        $table      = $this->resourceConnection->getTableName('gtstudio_ai_agent');

        $select = $connection->select()
            ->from($table, ['entity_id', 'code', 'description'])
            ->order('code ASC');

        $options = [];
        foreach ($connection->fetchAll($select) as $row) {
            $label     = $row['code'];
            $desc      = trim((string)$row['description']);
            if ($desc !== '') {
                $label .= ' — ' . $desc;
            }
            $options[] = ['value' => (int)$row['entity_id'], 'label' => $label];
        }

        return $options;
    }
}
