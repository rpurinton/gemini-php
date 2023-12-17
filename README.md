# Gemini PHP

Gemini PHP is a PHP library for interacting with the Gemini AI platform. It provides a client for making requests to the Gemini API and a response class for handling the API responses.

## Prerequisites

PHP 8.2+
Composer

- Create a Google Cloud Project if you don't already have one.
- Add the Vertex AI API to your project.
- Create a Service Account in your project with Basic permissions.
- Add an API Key and download the credentials JSON.
- Save your credentials somewhere like /home/you/.google/myproject.json
  
## Installation

To install Gemini PHP, you can use Composer. Run the following command:

```bash
composer require rpurinton/gemini-php
```

## Usage

Here is an example of how to use the Gemini PHP library:

```php
<?php

require 'vendor/autoload.php';

use RPurinton\GeminiPHP\{GeminiClient, GeminiPrompt};

$client = new GeminiClient(
    'ai-project-123456', // Your Project ID
    'us-east4', // Google Cloud Region
    '/home/you/.google/ai-project-123456-7382b3944223.json', // Path to Service Account Credentials
    'gemini-pro' // AI Model to use gemini-pro / gemini-pro-vision
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
        'parts' => ['text' => 'Hello!']
    ],
    [
        'role' => 'assistant',
        'parts' => ['text' => 'Argh! What brings ye to my ship?']
    ],
    [
        'role' => 'user',
        'parts' => ['text' => 'Wow! You are a real-life pirate!']
    ]
];
$safetySettings = [
    [
        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
        'threshold' => 'BLOCK_LOW_AND_ABOVE'
    ]
];
$tools = [];
$prompt = new GeminiPrompt($generationConfig, $contents, $safetySettings, $tools);

// Send the prompt to the Gemini API and get the response
$response = $client->getResponse($prompt->toJson()); // Returns a GeminiResponse Object

// Get the usage metadata
$usageMetadata = $response->getUsageMetadata();

echo $response->getText() . PHP_EOL;

```

## Table of Contents

- [Project Documentation](docs/README.md)
  - [Introduction](docs/introduction.md)
  - [Installation](docs/installation.md)
  - [Getting Started](docs/getting-started.md)
  - [Configuration](docs/configuration.md)
  - [Usage Examples](docs/usage-examples.md)
  - [API Reference](docs/api-reference.md)
  - [Troubleshooting](docs/troubleshooting.md)
  - [FAQ](docs/faq.md)
  - [Contributing](docs/contributing.md)
  - [License](docs/license.md)

For more detailed usage instructions, please refer to the [Gemini PHP documentation](docs).

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
