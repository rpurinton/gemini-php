<?php

use RPurinton\GeminiPHP\{GeminiClient, GeminiPrompt};

require_once __DIR__ . '/vendor/autoload.php';

$projectId = 'YOUR_PROJECT_ID';
$region = 'YOUR_REGION';
$accessToken = 'YOUR_ACCESS_TOKEN';
$modelName = 'gemini-pro'; // or 'gemini-pro-vision'

// Initialize the Gemini client
$client = new GeminiClient($projectId, $region, $accessToken, $modelName);

// Create a prompt object
$prompt = new GeminiPrompt($generationConfig, $contents, $tools, $safetySettings);

// Send the prompt to the Gemini API and get the response
$response = $client->getResponse($prompt->toJson());

// Get the generated content candidates
$candidates = $response->getCandidates();

// Get the usage metadata
$usageMetadata = $response->getUsageMetadata();
