# Gemini PHP

Gemini PHP is a PHP library for interacting with the Gemini AI platform. It provides a client for making requests to the Gemini API and a response class for handling the API responses.

## Installation

To install Gemini PHP, you can use Composer. Run the following command:

```
composer require rpurinton/gemini-php
```

## Usage

Here is an example of how to use the Gemini PHP library:

```php
<?php

require 'vendor/autoload.php';

use RPurinton\GeminiPHP\GeminiClient;

$client = new GeminiClient('project-id', 'region-name', 'credentials-path', 'model-name');
$response = $client->getResponse($promptData);

$text = $response->getText();
$metadata = $response->getUsageMetadata();

// Use the text and metadata as needed

```

For more detailed usage instructions, please refer to the [Gemini PHP documentation](https://github.com/rpurinton/gemini-php).

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
