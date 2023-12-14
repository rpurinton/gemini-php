<?php

namespace RPurinton\GeminiPHP;

class GeminiClient
{
    private $projectId;
    private $region;
    private $accessToken;

    public function __construct($projectId, $region, $accessToken)
    {
        // Initialize the client with project ID, region, and access token
    }

    public function streamGenerateContent($promptData)
    {
        // Make a POST request to the Gemini API endpoint
        // Use the provided prompt data to generate content
        // Return the response from the API
    }
}
