<?php

namespace RPurinton\GeminiPHP;

class GeminiClient
{
    private $accessToken;

    public function __construct(
        private string $projectId,
        private string $regionName,
        private string $credentialsPath,
        private string $modelName
    ) {
        // Access Token (uses gcloud CLI)
        $cmd = 'export GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath . ' && gcloud auth application-default print-access-token';
        $this->accessToken = trim(shell_exec($cmd) ?? '');
        if (empty($this->accessToken)) throw new \Exception('Error: Unable to get access token.');
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
        return 'https://' . $this->regionName .
            '-aiplatform.googleapis.com/v1' .
            '/projects/' . $this->projectId .
            '/locations/' . $this->regionName .
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
