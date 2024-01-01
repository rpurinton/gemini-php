# Usage Examples

Gemini PHP can be used in a variety of ways to enhance your application with AI-powered features. Below are some examples of how you can use the library.

## Basic Usage

Here's a simple example of how to send a prompt and receive a response:

```php
use RPurinton\GeminiPHP\{GeminiClient, GeminiPrompt};

$client = new GeminiClient([...]);
$prompt = new GeminiPrompt([...]);

$response = $client->getResponse($prompt->toJson());
$assistant_output = $response->getText();
```

## Advanced Usage

You can customize the prompt with different settings and tools to tailor the AI's response to your needs:

```php
$prompt->setGenerationConfig([...]);
$prompt->setSafetySettings([...]);
$prompt->setTools([...]);
```

For more detailed examples, refer to the `example.php` file and the API reference documentation.