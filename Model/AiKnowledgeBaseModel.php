<?php

namespace Gtstudio\AiKnowledgeBase\Model;

use Gtstudio\AiKnowledgeBase\Model\ResourceModel\AiKnowledgeBaseResource;
use Magento\Framework\Model\AbstractModel;

class AiKnowledgeBaseModel extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gtstudio_ai_knowledge_base_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(AiKnowledgeBaseResource::class);
    }
}
