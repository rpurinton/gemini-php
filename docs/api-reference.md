# API Reference

The Gemini PHP library provides several classes that you can use to interact with the Gemini AI platform. This reference provides a brief overview of the available classes and their methods.

## Classes

- `GeminiClient`: Handles API communication and authentication.
  - `__construct(array $config)`: Initializes the client with configuration.
  - `getResponse(string $promptData)`: Sends a prompt to the API and returns a `GeminiResponse`.

- `GeminiPrompt`: Manages the creation of prompts.
  - `__construct(array $config)`: Initializes the prompt with configuration.
  - `push(array $new_content)`: Adds new content to the prompt.
  - `setContent(array $new_content)`: Sets the entire content of the prompt.
  - `toJson()`: Converts the prompt to a JSON string for the API request.

- `GeminiResponse`: Processes API responses.
  - `__construct(array $response)`: Initializes the response object.
  - `getText()`: Retrieves the text from the response.
  - `getUsageMetadata()`: Retrieves usage metadata from the response.

- `HTTPClient`: Utility class for HTTP requests.
  - `post(string $url, array $headers, string $data)`: Performs a POST request.

- `Validate`: Utility class for data validation.
  - `contents(mixed $contents)`: Validates the contents structure.
  - `generationConfig(mixed $generation_config)`: Validates the generation config.
  - `safetySettings(mixed $safety_settings)`: Validates the safety settings.
  - `tools(mixed $tools)`: Validates the tools structure.

For detailed information on each class and method, please refer to the source code documentation.