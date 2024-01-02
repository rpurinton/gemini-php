<?php

namespace RPurinton\GeminiPHP;

use TikToken\Encoder;

/**
 * Class GeminiPrompt
 * @package RPurinton\GeminiPHP
 */
class GeminiPrompt
{
    private ?array $generation_config;
    private ?array $base_contents;
    private ?array $contents;
    private ?array $safety_settings;
    private ?array $tools;
    private ?Encoder $encoder;

    /**
     * GeminiPrompt constructor.
     *
     * Initializes a new instance of the GeminiPrompt with the provided configuration.
     * The configuration array should include the following keys:
     * - 'generation_config': The configuration for the generation process.
     * - 'contents': The contents of the prompt.
     * - 'safety_settings' (optional): The safety settings for the prompt. If not provided, defaults to an empty array.
     * - 'tools' (optional): The tools for the prompt. If not provided, defaults to an empty array.
     *
     * @param array $config The configuration array.
     * @throws \Exception If the validation of the configuration fails.
     */
    public function __construct(array $config)
    {
        $this->generation_config = $config['generation_config'] ?? [];
        $this->base_contents = $config['contents'] ?? [];
        $this->contents = $config['contents'] ?? [];
        $this->safety_settings = $config['safety_settings'] ?? [];
        $this->tools = $config['tools'] ?? [];
        $this->encoder = new Encoder();
        $this->validate();
    }

    /**
     * Pushes content to the contents array.
     *
     * The content array should contain two keys:
     * - 'role': A string that should be either 'user' or 'assistant'.
     * - 'parts': An array that typically contains a 'text' key but can also contain 'images' or other supported parts.
     *
     * @param array $content The content array to push.
     * @throws \Exception If the content validation fails.
     */
    public function push($content): void
    {
        $possible_contents = array_merge($this->contents, [$content]);
        Validate::contents($possible_contents) or throw new \Exception('Error: Content validation failed.');
        $this->contents[] = $content;
    }

    /**
     * Sets the contents array.
     *
     * The contents array should be an array of content arrays. Each content array should contain two keys:
     * - 'role': A string that should be either 'user' or 'assistant'.
     * - 'parts': An array that typically contains a 'text' key but can also contain 'images' or other supported parts.
     *
     * @param array $contents The array of content arrays to set.
     * @throws \Exception If the content validation fails.
     */
    public function setContent($contents): void
    {
        Validate::contents($contents) or throw new \Exception('Error: Contents validation failed.');
        $this->contents = $contents;
    }

    /**
     * Resets the contents array to the base contents array.
     * 
     * The base contents array should be an array of content arrays. Each content array should contain two keys:
     * - 'role': A string that should be either 'user' or 'assistant'.
     * - 'parts': An array that typically contains a 'text' key but can also contain 'images' or other supported parts.
     * 
     * @throws \Exception If the content validation fails.
     * @see setContent()
     */
    public function resetContent(): void
    {
        Validate::contents($this->base_contents) or throw new \Exception('Error: Base contents validation failed.');
        $this->contents = $this->base_contents;
    }

    /**
     * Sets the tools array.
     *
     * The tools array should fit the Gemini OpenAPI object schema for function calling definitions.
     * Each tool is an array that defines a function call, with keys for the function name and arguments.
     *
     * @param array $tools The array of tools to set.
     * @throws \Exception If the tools validation fails.
     */
    public function setTools(array $tools): void
    {
        Validate::tools($tools) or throw new \Exception('Error: Tools validation failed.');
        $this->tools = $tools;
    }

    /**
     * Sets the safety settings array.
     * @param array $safety_settings
     * @throws \Exception
     */
    public function setSafetySettings(array $safety_settings): void
    {
        Validate::safetySettings($safety_settings) or throw new \Exception('Error: Safety settings validation failed.');
        $this->safety_settings = $safety_settings;
    }

    /**
     * Sets the generation config array.
     * @param array $generation_config
     * @throws \Exception
     */
    public function setGenerationConfig(array $generation_config): void
    {
        Validate::generationConfig($generation_config) or throw new \Exception('Error: Generation config validation failed.');
        $this->generation_config = $generation_config;
    }

    /**
     * Returns the token count of the given text.
     * @param $text
     * @return int
     */
    public function token_count($text)
    {
        return count($this->encoder->encode($text));
    }

    /**
     * Returns the JSON representation of the GeminiPrompt object.
     * @return string
     * @throws \Exception
     */
    public function toJson(): string
    {
        $this->validate();
        return json_encode([
            'contents' => $this->contents,
            'tools' => $this->tools,
            'safety_settings' => $this->safety_settings,
            'generation_config' => $this->generation_config
        ]);
    }

    /**
     * Validates the GeminiPrompt object.
     * @return bool
     * @throws \Exception
     */
    public function validate(): bool
    {
        Validate::contents($this->contents) or throw new \Exception('Error: Content validation failed.');
        Validate::tools($this->tools) or throw new \Exception('Error: Tools validation failed.');
        Validate::safetySettings($this->safety_settings) or throw new \Exception('Error: Safety settings validation failed.');
        Validate::generationConfig($this->generation_config) or throw new \Exception('Error: Generation config validation failed.');
        return true;
    }
}
