# Gtstudio_AiKnowledgeBase

Document management for AI agents in Magento 2. Upload files that agents can retrieve as context before answering queries — enabling retrieval-augmented generation (RAG) without a vector database.

## Preview

![AiKnowledgeBase — uploading a PDF and querying an agent that retrieves relevant excerpts](docs/images/aiknowledgebase-preview.gif)

## AI Studio Ecosystem

Part of the **AI Studio** suite for Magento 2. See all modules:

| Module | Repository | Description |
|--------|-----------|-------------|
| **Gtstudio_AiConnector** | [module-aiconnector](https://github.com/gabrielgts/module-aiconnector) | Core AI provider abstraction |
| **Gtstudio_AiAgents** | [module-ai-agents](https://github.com/gabrielgts/module-ai-agents) | Agent & tool orchestration, cron scheduling, execution log |
| **Gtstudio_AiWidgets** | [module-ai-widgets](https://github.com/gabrielgts/module-ai-widgets) | Floating admin chat widget + PageBuilder AI generator |
| **Gtstudio_AiDataQuery** | [module-ai-data-query](https://github.com/gabrielgts/module-ai-data-query) | Natural-language store analytics (privacy-first) |
| **Gtstudio_AiKnowledgeBase** | *(this module)* | Document upload & RAG retrieval for agents |
| **Gtstudio_AiDashboard** | *(coming soon)* | AI-powered KPI dashboard with ML insights |

## What It Does

- Upload and manage documents (PDF, TXT) in the Magento admin
- Documents are stored and indexed so that agents can fetch relevant excerpts at query time
- Integrates with `Gtstudio_AiAgents` — assign a knowledge base to any agent

## Requirements

- Magento 2.4.4+
- PHP 8.1+
- `Gtstudio_AiConnector` enabled and configured
- `Gtstudio_AiAgents` enabled
- `smalot/pdfparser: ^2.12` (PDF text extraction)

## Installation

```bash
composer require gtstudio/module-ai-knowledge-base
php bin/magento module:enable Gtstudio_AiKnowledgeBase
php bin/magento setup:upgrade
```

## Usage

### Uploading Documents

Navigate to *AI Studio → Agents & Tools → Knowledge Base*.

Click **Add New**, fill in:

| Field | Description |
|-------|-------------|
| Title | Human-readable label (auto-populated from PDF metadata on upload) |
| Upload PDF Document | Upload a PDF file — text and metadata are extracted automatically |
| Content | Extracted text (editable; used for retrieval) |
| Tags | Comma-separated keywords (auto-populated from PDF metadata) |
| Agents | Associate this document with one or more agents |
| Is Active | Only active entries are searchable by agents |

### How Retrieval Works

When an agent that has knowledge base documents attached receives a question:

1. The question is matched against document excerpts using keyword or semantic similarity
2. Relevant excerpts are prepended to the agent's system prompt as context
3. The agent responds with awareness of those excerpts

No full document text is sent to the LLM — only the most relevant excerpts, keeping token usage low.

## Extensibility

### Supporting Additional File Formats

The text extraction pipeline uses a registry pattern. Register a custom extractor for a new MIME type:

```xml
<!-- etc/di.xml -->
<type name="Gtstudio\AiKnowledgeBase\Model\Extractor\ExtractorPool">
    <arguments>
        <argument name="extractors" xsi:type="array">
            <item name="application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                  xsi:type="object">
                Vendor\Module\Model\Extractor\DocxExtractor
            </item>
        </argument>
    </arguments>
</type>
```

Implement `Gtstudio\AiKnowledgeBase\Api\ExtractorInterface`:

```php
interface ExtractorInterface
{
    /**
     * Extract plain text from the given file path.
     */
    public function extract(string $filePath): string;
}
```

### Custom Retrieval Strategy

Override the retrieval service to use a vector database, OpenSearch k-NN, or any other similarity search:

```xml
<preference for="Gtstudio\AiKnowledgeBase\Api\RetrievalServiceInterface"
            type="Vendor\Module\Model\VectorRetrievalService"/>
```

### Chunking Strategy

Document chunking (splitting documents into excerpt-sized pieces) can be customised:

```xml
<type name="Gtstudio\AiKnowledgeBase\Model\Chunker\TextChunker">
    <arguments>
        <!-- Maximum characters per chunk -->
        <argument name="chunkSize" xsi:type="number">1500</argument>
        <!-- Overlap between consecutive chunks -->
        <argument name="overlap" xsi:type="number">200</argument>
    </arguments>
</type>
```

## Database Tables

| Table | Purpose |
|-------|---------|
| `gtstudio_ai_knowledge_base` | Document metadata (name, description, file path, agent association) |
| `gtstudio_ai_knowledge_base_chunk` | Extracted text chunks ready for retrieval |

## ACL Resources

| Resource | Controls |
|----------|---------|
| `Gtstudio_AiKnowledgeBase::management` | Access to the Knowledge Base admin section |
