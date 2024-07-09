<?php

namespace App\Service;

readonly class OpenApiClient
{
    public function __construct(
        private string $openAiKey,
    ) {}

    public function __invoke(): \OpenAI\Client
    {
        return \OpenAI::factory()
            ->withApiKey($this->openAiKey)
            ->withHttpClient(new \GuzzleHttp\Client(['timeout' => 240]))
            ->make()
        ;

        return \OpenAI::client($this->openAiKey);
    }
}
