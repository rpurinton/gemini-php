<?php

namespace RPurinton\GeminiPHP;

use Google\Auth\ApplicationDefaultCredentials;

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
            $this->validateCredentials();
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $this->credentialsPath);
            $accessToken = ApplicationDefaultCredentials::getCredentials('https://www.googleapis.com/auth/cloud-platform')->fetchAuthToken();
            if (!isset($accessToken['access_token'])) throw new \Exception('Error: Unable to get access token.');
            $this->accessToken = $accessToken['access_token'];
            $this->expiresAt = time() + self::VALID_TIME;
        }
    }

    public function validateCredentials(): void
    {
        if (!file_exists($this->credentialsPath)) throw new \Exception('Error: Credentials file not found.');
        if (!is_readable($this->credentialsPath)) throw new \Exception('Error: Credentials file not readable.');
        $contents = file_get_contents($this->credentialsPath) or throw new \Exception('Error: Unable to read credentials file.');
        $json = json_decode($contents, true) or throw new \Exception('Error: Unable to parse credentials file.');
        if (!isset($json['project_id'])) throw new \Exception('Error: Credentials file missing project_id.');
        if (!isset($json['client_email'])) throw new \Exception('Error: Credentials file missing client_email.');
        if (!isset($json['private_key'])) throw new \Exception('Error: Credentials file missing private_key.');
        if (!isset($json['type'])) throw new \Exception('Error: Credentials file missing type.');
        if ($json['type'] !== 'service_account') throw new \Exception('Error: Credentials file type must be service_account.');
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
