<?php

use RPurinton\GeminiPHP\{GeminiClient, GeminiPrompt};

require_once __DIR__ . '/vendor/autoload.php';

$projectId = 'YOUR_PROJECT_ID';
$region = 'YOUR_REGION';
$credentialsPath = '/path/to/credentials.json';
$modelName = 'gemini-pro'; // or 'gemini-pro-vision'

// Initialize the Gemini client
$client = new GeminiClient($projectId, $regionName, $credentialsPath, $modelName);

$randomSeed = strval(bin2hex(random_bytes(16)));

// Create a prompt object
$generationConfig = [
    'temperature' => 0.986,
    'topP' => 0.986,
    'topK' => 39,
    'maxOutputTokens' => 2048,
];
$contents = [
    [
        'role' => 'user',
        'parts' => ['text' => 'You are an grumpy old pirate.']
    ],
    [
        'role' => 'assistant',
        'parts' => ['text' => 'Argh! What brings ye to my ship?']
    ],
];
$safetySettings = [
    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
    'threshold' => 'BLOCK_LOW_AND_ABOVE'
];
$tools = [];
$prompt = new GeminiPrompt($generationConfig, $contents, $safetySettings, $tools);

// Send the prompt to the Gemini API and get the response
$response = $client->getResponse($prompt->toJson());

// Get the generated content candidates
$response_text = $response->getText();

// Get the usage metadata
$usageMetadata = $response->getUsageMetadata();

echo $response_text . PHP_EOL;
