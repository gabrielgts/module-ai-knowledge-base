<?php

namespace Gtstudio\AiKnowledgeBase\Api\Data;

interface AiKnowledgeBaseInterface
{
    /**
     * String constants for property names
     */
    public const ENTITY_ID = "entity_id";
    public const TITLE = "title";
    public const CONTENT = "content";
    public const TAGS = "tags";
    public const IS_ACTIVE = "is_active";
    public const AGENT_IDS = "agent_ids";
    public const CREATED_AT = "created_at";
    public const UPDATED_AT = "updated_at";

    /**
     * Getter for EntityId.
     *
     * @return int|null
     */
    public function getEntityId(): ?int;

    /**
     * Setter for EntityId.
     *
     * @param int|null $entityId
     *
     * @return void
     */
    public function setEntityId(?int $entityId): void;

    /**
     * Getter for Title.
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Setter for Title.
     *
     * @param string|null $title
     *
     * @return void
     */
    public function setTitle(?string $title): void;

    /**
     * Getter for Content.
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Setter for Content.
     *
     * @param string|null $content
     *
     * @return void
     */
    public function setContent(?string $content): void;

    /**
     * Getter for Tags.
     *
     * @return string|null
     */
    public function getTags(): ?string;

    /**
     * Setter for Tags.
     *
     * @param string|null $tags
     *
     * @return void
     */
    public function setTags(?string $tags): void;

    /**
     * Getter for IsActive.
     *
     * @return bool|null
     */
    public function getIsActive(): ?bool;

    /**
     * Setter for IsActive.
     *
     * @param bool|null $isActive
     *
     * @return void
     */
    public function setIsActive(?bool $isActive): void;

    /**
     * Getter for AgentIds.
     *
     * @return string|null Comma-separated agent entity IDs.
     */
    public function getAgentIds(): ?string;

    /**
     * Setter for AgentIds.
     *
     * @param string|null $agentIds Comma-separated agent entity IDs.
     * @return void
     */
    public function setAgentIds(?string $agentIds): void;

    /**
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void;

    /**
     * Getter for UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Setter for UpdatedAt.
     *
     * @param string|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void;
}
