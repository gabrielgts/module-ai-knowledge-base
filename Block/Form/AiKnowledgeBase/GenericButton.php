<?php

namespace Gtstudio\AiKnowledgeBase\Block\Form\AiKnowledgeBase;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;

/**
 * Generic (form) button for AiKnowledgeBase entity.
 */
class GenericButton
{
    /**
     * @var Context
     */
    private Context $context;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        $this->context = $context;
        $this->urlBuilder = $context->getUrlBuilder();
    }

    /**
     * Get AiKnowledgeBase entity id.
     *
     * @return int
     */
    public function getEntityId(): int
    {
        return (int)$this->context->getRequest()->getParam(AiKnowledgeBaseInterface::ENTITY_ID);
    }

    /**
     * Wrap button specific options to settings array.
     *
     * @param string $label
     * @param string $class
     * @param string $onclick
     * @param array $dataAttribute
     * @param int $sortOrder
     *
     * @return array
     */
    protected function wrapButtonSettings(
        string $label,
        string $class,
        string $onclick = '',
        array  $dataAttribute = [],
        int    $sortOrder = 0
    ): array
    {
        return [
            'label' => $label,
            'on_click' => $onclick,
            'data_attribute' => $dataAttribute,
            'class' => $class,
            'sort_order' => $sortOrder
        ];
    }

    /**
     * Get url.
     *
     * @param string $route
     * @param array $params
     *
     * @return string
     */
    protected function getUrl(string $route, array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
