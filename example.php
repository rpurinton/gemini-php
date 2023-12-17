<?php

require 'vendor/autoload.php';

use RPurinton\GeminiPHP\{GeminiClient, GeminiPrompt};

$client = new GeminiClient(
    'ai-project-123456', // Your Project ID
    'us-east4', // Google Cloud Region
    '/home/you/.google/ai-project-123456-7382b3944223.json', // Path to Service Account Credentials
    'gemini-pro', // AI Model to use gemini-pro / gemini-pro-vision
);

// Create a prompt object (max values shown)
$generationConfig = [
    'temperature' => 1.0,
    'topP' => 1.0,
    'topK' => 40,
    'maxOutputTokens' => 2048,
];
$contents = [
    [
        'role' => 'user',
        'parts' => ['text' => 'Hello!'],
    ],
    [
        'role' => 'assistant',
        'parts' => ['text' => 'Argh! What brings ye to my ship?'],
    ],
    [
        'role' => 'user',
        'parts' => ['text' => 'Wow! You are a real-life pirate!'],
    ],
];
$safetySettings = [
    [
        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
        'threshold' => 'BLOCK_LOW_AND_ABOVE',
    ],
];
$tools = [];
$prompt = new GeminiPrompt($generationConfig, $contents, $safetySettings, $tools);

// Send the prompt to the Gemini API and get the response
$response = $client->getResponse($prompt->toJson()); // Returns a GeminiResponse Object

// Get the usage metadata
$usageMetadata = $response->getUsageMetadata();

echo $response->getText() . PHP_EOL;
