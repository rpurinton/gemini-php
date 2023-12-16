<?php

namespace RPurinton\GeminiPHP;

class GeminiClient
{
    private $accessToken;
    private $expiresAt = 0;

    public function __construct(
        private string $projectId,
        private string $regionName,
        private string $credentialsPath,
        private string $modelName
    ) {
        // Access Token (uses gcloud CLI)
        $cmd = 'export GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath . ' && gcloud auth application-default print-access-token --expiration=86400';
        $this->accessToken = trim(shell_exec($cmd) ?? '');
        $this->expiresAt = time() + 86400;
        if (empty($this->accessToken)) throw new \Exception('Error: Unable to get access token.');
    }

    public function getResponse($promptData): GeminiResponse
    {
        if (time() > $this->expiresAt) {
            $cmd = 'export GOOGLE_APPLICATION_CREDENTIALS=' . $this->credentialsPath . ' && gcloud auth application-default print-access-token --expiration=86400';
            $this->accessToken = trim(shell_exec($cmd) ?? '');
            $this->expiresAt = time() + 86400;
            if (empty($this->accessToken)) throw new \Exception('Error: Unable to get access token.');
        }
        $response_json = HTTPClient::post($this->buildUrl(), $this->buildHeaders(), $promptData);
        return new GeminiResponse(json_decode($response_json, true));
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
