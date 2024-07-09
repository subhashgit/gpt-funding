<?php

namespace App\Service;

use OpenAI\Responses\Embeddings\CreateResponse;

readonly class EmbeddingService
{
    public function __construct(
        private Tokenizer $tokenizer,
        private OpenApiClient $openApiClient,
    ) {}

    public function generateEmbedding(string $text): CreateResponse
    {
        return ($this->openApiClient)()->embeddings()->create([
            'input' => $this->tokenizer->preprocessText($text),
            'model' => 'text-embedding-ada-002',
            'max_tokens' => 256,
            'temperature' => 0.9,
            'top_p' => 1,
        ]);
    }
}
