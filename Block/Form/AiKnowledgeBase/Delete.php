<?php

namespace Gtstudio\AiKnowledgeBase\Block\Form\AiKnowledgeBase;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Delete entity button.
 */
class Delete extends GenericButton implements ButtonProviderInterface
{
    /**
     * Retrieve Delete button settings.
     *
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->getEntityId()) {
            return [];
        }

        return $this->wrapButtonSettings(
            __('Delete')->getText(),
            'delete',
            sprintf("deleteConfirm('%s', '%s')",
                __('Are you sure you want to delete this aiknowledgebase?'),
                $this->getUrl(
                    '*/*/delete',
                    [AiKnowledgeBaseInterface::ENTITY_ID => $this->getEntityId()]
                )
            ),
            [],
            20
        );
    }
}
