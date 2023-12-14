<?php

use RPurinton\GeminiPHP\{GeminiClient, GeminiPrompt, GeminiResponse};

require_once __DIR__ . '/vendor/autoload.php';

// Initialize the Gemini client
$client = new GeminiClient($projectId, $region, $accessToken);

// Create a prompt object
$prompt = new GeminiPrompt($contents, $tools, $safetySettings, $generationConfig);

// Send the prompt to the Gemini API and get the response
$response = $client->streamGenerateContent($prompt->toJson());

// Process the response
$geminiResponse = new GeminiResponse($response['candidates'], $response['usageMetadata']);

// Get the generated content candidates
$candidates = $geminiResponse->getCandidates();

// Get the usage metadata
$usageMetadata = $geminiResponse->getUsageMetadata();
