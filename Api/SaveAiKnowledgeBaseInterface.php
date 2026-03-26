<?php

namespace Gtstudio\AiKnowledgeBase\Api;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Save AiKnowledgeBase Command.
 *
 * @api
 */
interface SaveAiKnowledgeBaseInterface
{
    /**
     * Save AiKnowledgeBase.
     *
     * @param \Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface $aiKnowledgeBase
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(AiKnowledgeBaseInterface $aiKnowledgeBase): int;
}
