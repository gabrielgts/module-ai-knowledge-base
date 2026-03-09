<?php

namespace Gtstudio\AiKnowledgeBase\Model\ResourceModel\AiKnowledgeBaseModel;

use Gtstudio\AiKnowledgeBase\Model\AiKnowledgeBaseModel;
use Gtstudio\AiKnowledgeBase\Model\ResourceModel\AiKnowledgeBaseResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class AiKnowledgeBaseCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gtstudio_ai_knowledge_base_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(AiKnowledgeBaseModel::class, AiKnowledgeBaseResource::class);
    }
}
