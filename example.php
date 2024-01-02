<?php

require __DIR__ . '/vendor/autoload.php';

use RPurinton\GeminiPHP\{GeminiClient, GeminiPrompt};

// See:
// https://github.com/rpurinton/gemini-php
// https://cloud.google.com/vertex-ai/docs/generative-ai/model-reference/overview


try {
    // Create a GeminiClient object
    $example_client = file_get_contents(__DIR__ . '/example-client.json') or throw new \Exception('Unable to read example-client.json');
    $example_client = json_decode($example_client, true) or throw new \Exception('Unable to decode example-client.json');
    $client = new GeminiClient($example_client) or throw new \Exception('Unable to create GeminiClient object');
    // Create a GeminiPrompt object
    $example_prompt = file_get_contents(__DIR__ . '/example-prompt.json') or throw new \Exception('Unable to read example-prompt.json');
    $example_prompt = json_decode($example_prompt, true) or throw new \Exception('Unable to decode example-prompt.json');
    $prompt = new GeminiPrompt($example_prompt) or throw new \Exception('Unable to create GeminiPrompt object');
} catch (\Exception $e) {
    echo ('fatal> ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

// Create a function to handle commands
$commands = function ($user_input) use ($prompt): bool {
    $command = strtolower($user_input);
    switch ($command) {
        case 'exit':
        case 'quit':
            exit(0);
        case 'clear':
            $prompt->resetContent();
            echo ('Prompt cleared.' . PHP_EOL);
            return true;
        case 'help':
            echo ('Commands: exit, quit, clear, help' . PHP_EOL);
            return true;
        default:
            return false;
    }
};

echo ('Press CTRL+C to exit...' . PHP_EOL);
while (true) {
    try {
        // Get user input
        $user_input = readline('user> ');

        // Check for commands
        if ($commands($user_input)) continue;

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
    } catch (\Exception $e) {
        echo ('error> ' . $e->getMessage() . PHP_EOL);
    }
}
