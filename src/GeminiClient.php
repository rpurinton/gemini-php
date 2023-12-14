<?php

namespace RPurinton\GeminiPHP;

class GeminiClient
{

    public function __construct(
        private int|string $projectId,
        private string $region,
        private string $accessToken,
        private string $modelName
    ) {
        // Initialize the client with project ID, region, access token, and model name
    }

    public function getResponse($promptData): GeminiResponse
    {
        // Make a POST request to the Gemini API endpoint
        // Use the provided prompt data to generate content
        $url = "https://{$this->region}-aiplatform.googleapis.com/v1/projects/{$this->projectId}/locations/{$this->region}/publishers/google/models/{$this->modelName}:streamGenerateContent";
        $response = json_decode(HTTPClient::post($url, $promptData, [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json; charset=utf-8',
        ]), true);

        // Get the generated content candidates from the response
        $candidates = $response['candidates'] ?? null;
        $usageMetadata = $response['usageMetadata'] ?? null;

        // Return the response from the API
        return new GeminiResponse($candidates, $usageMetadata);
    }
}
