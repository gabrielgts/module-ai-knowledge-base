<?php

namespace Gtstudio\AiKnowledgeBase\Controller\Adminhtml\AiKnowledgeBase;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * AiKnowledgeBase backend index (list) controller.
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    public const ADMIN_RESOURCE = 'Gtstudio_AiKnowledgeBase::management';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Gtstudio_AiKnowledgeBase::management');
        $resultPage->addBreadcrumb(__('AiKnowledgeBase'), __('AiKnowledgeBase'));
        $resultPage->addBreadcrumb(__('Manage AiKnowledgeBases'), __('Manage AiKnowledgeBases'));
        $resultPage->getConfig()->getTitle()->prepend(__('AiKnowledgeBase List'));

        return $resultPage;
    }
}
