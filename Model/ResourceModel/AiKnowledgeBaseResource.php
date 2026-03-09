<?php

namespace Gtstudio\AiKnowledgeBase\Model\ResourceModel;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AiKnowledgeBaseResource extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gtstudio_ai_knowledge_base_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('gtstudio_ai_knowledge_base', AiKnowledgeBaseInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }
}
