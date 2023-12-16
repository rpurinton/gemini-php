<?php

namespace RPurinton\GeminiPHP;

class GeminiClient
{
    private ?string $credentialsPath;
    private ?string $projectId;
    private ?string $regionName;
    private ?string $modelName;
    private ?string $accessToken;
    private $expiresAt = 0;
    const VALID_TIME = 3600;

    public function __construct(private $config)
    {
        $this->credentialsPath = $config['credentialsPath'];
        $this->projectId = $config['projectId'];
        $this->regionName = $config['regionName'];
        $this->modelName = $config['modelName'];
        $this->refreshAccessToken();
    }

    public function refreshAccessToken(): void
    {
        if (time() > $this->expiresAt) {
            $gcloud = trim(shell_exec('which gcloud') ?? '');
            if (empty($gcloud)) throw new \Exception('Error: gcloud not found.');
            if (!file_exists($this->credentialsPath)) throw new \Exception('Error: credentials file not found.');
            $credentials = json_decode(file_get_contents($this->credentialsPath), true);
            if (!$credentials) throw new \Exception('Error: credentials file JSON error.');
            $cmd = 'export GOOGLE_APPLICATION_CREDENTIALS=' . $this->credentialsPath . ' && ' . $gcloud . ' auth application-default print-access-token';
            $this->accessToken = trim(shell_exec($cmd) ?? '');
            $this->expiresAt = time() + self::VALID_TIME;
            if (empty($this->accessToken)) throw new \Exception('Error: Unable to get access token.');
        }
    }

    public function getResponse($promptData): GeminiResponse
    {
        $this->refreshAccessToken();
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
