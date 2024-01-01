# API Reference

The Gemini PHP library provides several classes that you can use to interact with the Gemini AI platform. This reference provides a brief overview of the available classes and their methods.

## Classes

- `GeminiClient`: Handles API communication and authentication.
  - `__construct(array $config)`: Initializes the client with configuration.
  - `getResponse(string $promptData)`: Sends a prompt to the API and returns a `GeminiResponse`.

- `GeminiPrompt`: Manages the creation of prompts.
  - `__construct(array $config)`: Initializes the prompt with configuration.
  - `push(array $new_content)`: Adds new content to the prompt. Throws an exception if content validation fails.
  - `toJson()`: Converts the prompt to a JSON string for the API request. Throws an exception if any validation fails.
  - `setContent(array $new_content)`: Sets the entire content of the prompt. Throws an exception if content validation fails.
  - `getContents()`: Retrieves the current contents of the prompt.
  - `getGenerationConfig()`: Retrieves the current generation configuration of the prompt.
  - `getSafetySettings()`: Retrieves the current safety settings of the prompt.
  - `getTools()`: Retrieves the current tools of the prompt.
  
- `GeminiResponse`: Processes API responses.
  - `__construct(array $response)`: Initializes the response object.
  - `getText()`: Retrieves the text from the response. Throws an exception if unable to parse the response.
  - `getUsageMetadata()`: Retrieves usage metadata from the response.

- `HTTPClient`: Utility class for HTTP requests.
  - `post(string $url, array $headers, string $data)`: Performs a POST request. Throws an exception if the HTTP request fails.

- `Validate`: Utility class for data validation.
  - `contents(mixed $contents)`: Validates the contents structure. Returns true if validation passes, throws an exception otherwise.
  - `generationConfig(mixed $generation_config)`: Validates the generation config. Returns true if validation passes, throws an exception otherwise.
  - `safetySettings(mixed $safety_settings)`: Validates the safety settings. Returns true if validation passes, throws an exception otherwise.
  - `tools(mixed $tools)`: Validates the tools structure. Returns true if validation passes, throws an exception otherwise.

For detailed information on each class and method, please refer to the source code documentation.
