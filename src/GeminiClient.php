<?php

namespace RPurinton\GeminiPHP;

class GeminiClient
{

    public function __construct(
        private string $projectId,
        private string $region,
        private string $accessToken,
        private string $modelName
    ) {
        // Initialize the Gemini client with the provided credentials
    }

    public function getResponse($promptData): GeminiResponse
    {
        $response_json = HTTPClient::post($this->buildUrl(), $this->buildHeaders(), $promptData);
        $response = json_decode($response_json, true);
        $candidates = $response['candidates'] ?? null;
        $usageMetadata = $response['usageMetadata'] ?? null;
        return new GeminiResponse($candidates, $usageMetadata);
    }

    private function buildUrl(): string
    {
        return 'https://' . $this->region .
            '-aiplatform.googleapis.com/v1' .
            '/projects/' . $this->projectId .
            '/locations/' . $this->region .
            '/publishers/google/models/' . $this->modelName .
            ':streamGenerateContent';
    }

    private function buildHeaders(): array
    {
        return [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json; charset=utf-8',
        ];
    }
}
