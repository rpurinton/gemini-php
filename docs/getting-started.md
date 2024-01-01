# Getting Started with Gemini PHP

After installing Gemini PHP, you can start using it to interact with the Gemini AI platform. Here's a quick guide to get you started:

## Initialize the Client

Create a new instance of `GeminiClient` with your configuration details:

```php
use RPurinton\GeminiPHP\GeminiClient;

$client = new GeminiClient([
    'projectId' => 'your-project-id',
    'regionName' => 'your-region',
    'credentialsPath' => 'path-to-your-credentials.json',
    'modelName' => 'gemini-pro',
]);
```

## Create a Prompt

Use `GeminiPrompt` to create a new prompt:

```php
use RPurinton\GeminiPHP\GeminiPrompt;

$prompt = new GeminiPrompt([
    'generation_config' => [
        'temperature' => 0.7,
        'topP' => 0.9,
        'topK' => 40,
        'maxOutputTokens' => 2048,
    ],
    'contents' => [
        [
            'role' => 'user',
            'parts' => ['text' => 'Your input here'],
        ],
    ],
]);
```

## Get a Response

Send the prompt to the Gemini API and receive a response:

```php
$response = $client->getResponse($prompt->toJson());
$assistant_output = $response->getText();
```

## Next Steps

Explore the various configuration options, experiment with different prompts, and integrate the responses into your application. For more detailed examples and API usage, refer to the `example.php` file and the rest of the documentation.