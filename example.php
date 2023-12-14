<?php

use RPurinton\GeminiPHP\{GeminiClient, GeminiPrompt};

require_once __DIR__ . '/vendor/autoload.php';

$projectId = 'YOUR_PROJECT_ID';
$region = 'YOUR_REGION';
$credentialsPath = '/path/to/credentials.json';
$modelName = 'gemini-pro'; // or 'gemini-pro-vision'

// Initialize the Gemini client
$client = new GeminiClient($projectId, $regionName, $credentialsPath, $modelName);

// Create a prompt object
$generationConfig = [
    'temperature' => 0.2,
    'topP' => 0.8,
    'topK' => 40,
    'maxOutputTokens' => 2048,
];
$contents = [
    [
        'role' => 'USER',
        'parts' => ['text' => 'Hello!']
    ],
    [
        'role' => 'ASSISTANT',
        'parts' => ['text' => 'Argh! What brings ye to my ship?']
    ],
    [
        'role' => 'USER',
        'parts' => ['text' => 'Wow! You are a real-life pirate!']
    ]
];
$tools = [];
$safetySettings = [
    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
    'threshold' => 'BLOCK_LOW_AND_ABOVE'
];
$prompt = new GeminiPrompt($generationConfig, $contents, $tools, $safetySettings);

// Send the prompt to the Gemini API and get the response
$response = $client->getResponse($prompt->toJson());

// Get the generated content candidates
$candidates = $response->getCandidates();

// Get the usage metadata
$usageMetadata = $response->getUsageMetadata();

print_r($candidates);
print_r($usageMetadata);
