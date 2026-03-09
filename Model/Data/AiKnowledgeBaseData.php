<?php

namespace Gtstudio\AiKnowledgeBase\Model\Data;

use Gtstudio\AiKnowledgeBase\Api\Data\AiKnowledgeBaseInterface;
use Magento\Framework\DataObject;

class AiKnowledgeBaseData extends DataObject implements AiKnowledgeBaseInterface
{
    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        return $this->getData(self::ENTITY_ID) === null ? null
            : (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * Setter for EntityId.
     *
     * @param int|null $entityId
     *
     * @return void
     */
    public function setEntityId(?int $entityId): void
    {
        $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Getter for Title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Setter for Title.
     *
     * @param string|null $title
     *
     * @return void
     */
    public function setTitle(?string $title): void
    {
        $this->setData(self::TITLE, $title);
    }

    /**
     * Getter for Content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Setter for Content.
     *
     * @param string|null $content
     *
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->setData(self::CONTENT, $content);
    }

    /**
     * Getter for Tags.
     *
     * @return string|null
     */
    public function getTags(): ?string
    {
        return $this->getData(self::TAGS);
    }

    /**
     * Setter for Tags.
     *
     * @param string|null $tags
     *
     * @return void
     */
    public function setTags(?string $tags): void
    {
        $this->setData(self::TAGS, $tags);
    }

    /**
     * Getter for IsActive.
     *
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->getData(self::IS_ACTIVE) === null ? null
            : (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * Setter for IsActive.
     *
     * @param bool|null $isActive
     *
     * @return void
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Getter for AgentIds.
     *
     * @return string|null
     */
    public function getAgentIds(): ?string
    {
        return $this->getData(self::AGENT_IDS);
    }

    /**
     * Setter for AgentIds.
     *
     * @param string|null $agentIds
     * @return void
     */
    public function setAgentIds(?string $agentIds): void
    {
        $this->setData(self::AGENT_IDS, $agentIds);
    }

    /**
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Getter for UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Setter for UpdatedAt.
     *
     * @param string|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
