<?php

namespace Gtstudio\AiKnowledgeBase\Controller\Adminhtml\AiKnowledgeBase;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * New action AiKnowledgeBase controller.
 */
class NewAction extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Gtstudio_AiKnowledgeBase::management';

    /**
     * Create new AiKnowledgeBase action.
     *
     * @return Page|ResultInterface
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Gtstudio_AiKnowledgeBase::management');
        $resultPage->getConfig()->getTitle()->prepend(__('New AiKnowledgeBase'));

        return $resultPage;
    }
}
