<?php

namespace Gtstudio\AiKnowledgeBase\Api;

use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete AiKnowledgeBase by id Command.
 *
 * @api
 */
interface DeleteAiKnowledgeBaseByIdInterface
{
    /**
     * Delete AiKnowledgeBase.
     * @param int $entityId
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $entityId): void;
}
