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
    private ?array $contents;
    private ?array $safety_settings;
    private ?array $tools;
    private ?Encoder $encoder;

    /**
     * GeminiPrompt constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->generation_config = $config['generation_config'];
        $this->contents = $config['contents'];
        $this->safety_settings = $config['safety_settings'] ?? [];
        $this->tools = $config['tools'] ?? [];
        $this->encoder = new Encoder();
        $this->validate();
    }

    /**
     * Pushes content to the contents array.
     * @param array $content
     * @throws \Exception
     */
    public function push(array $content): void
    {
        $this->contents[] = $content;
        Validate::contents($this->contents) or throw new \Exception('Error: Content validation failed.');
    }

    /**
     * Sets the contents array.
     * @param array $contents
     * @throws \Exception
     */
    public function setContent(array $contents): void
    {
        $this->contents = $contents;
        Validate::contents($this->contents) or throw new \Exception('Error: Content validation failed.');
    }

    /**
     * Sets the tools array.
     * @param array $tools
     * @throws \Exception
     */
    public function setTools(array $tools): void
    {
        $this->tools = $tools;
        Validate::tools($this->tools) or throw new \Exception('Error: Tools validation failed.');
    }

    /**
     * Sets the safety settings array.
     * @param array $safety_settings
     * @throws \Exception
     */
    public function setSafetySettings(array $safety_settings): void
    {
        $this->safety_settings = $safety_settings;
        Validate::safetySettings($this->safety_settings) or throw new \Exception('Error: Safety settings validation failed.');
    }

    /**
     * Sets the generation config array.
     * @param array $generation_config
     * @throws \Exception
     */
    public function setGenerationConfig(array $generation_config): void
    {
        $this->generation_config = $generation_config;
        Validate::generationConfig($this->generation_config) or throw new \Exception('Error: Generation config validation failed.');
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
