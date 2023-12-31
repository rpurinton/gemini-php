<?php

require __DIR__ . '/vendor/autoload.php';

use RPurinton\GeminiPHP\{GeminiClient, GeminiPrompt};

$client = new GeminiClient([
    'projectId' => 'ai-project-123456', // Your Project ID
    'regionName' => 'us-east4', // Google Cloud Region
    'credentialsPath' => '/home/you/.google/ai-project-123456-7382b3944223.json', // Path to Service Account Credentials
    'modelName' => 'gemini-pro', // AI Model to use gemini-pro / gemini-pro-vision
]);

$prompt = new GeminiPrompt([
    'generation_config' => [ // Max values shown
        'temperature' => 1.0,
        'topP' => 1.0,
        'topK' => 40,
        'maxOutputTokens' => 2048,
    ],
    'contents' => [ // Must alternate user/assistant/user/assistant
        [
            'role' => 'user',
            'parts' => ['text' => 'You are a helpful assistant.'],
        ],
        [
            'role' => 'assistant',
            'parts' => ['text' => 'I am a helpful assistant!'],
        ],
    ],
    'safety_settings' => [
        [
            'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
            'threshold' => 'BLOCK_LOW_AND_ABOVE',
        ],
    ],
    'tools' => [],
]);

echo ('Press CTRL+C to exit...\n');
while (true) {
    // Get user input
    $user_input = readline('user> ');

    // Add the user input to the prompt
    $prompt->push(['role' => 'user', 'parts' => ['text' => $user_input]]);

    // Send the prompt to the Gemini API and get the response
    $response = $client->getResponse($prompt->toJson()); // Returns a GeminiResponse Object

    // Get the usage metadata if you need it
    $usageMetadata = $response->getUsageMetadata();

    // Get the response text
    $assistant_output = $response->getText();

    // Display the response text
    echo ('assistant> ' . $assistant_output . PHP_EOL);

    // Add the response to the prompt
    $prompt->push(['role' => 'assistant', 'parts' => ['text' => $assistant_output]]);
}
