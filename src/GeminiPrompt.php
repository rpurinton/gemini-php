<?php

namespace RPurinton\GeminiPHP;

use TikToken\Encoder;

/**
 * Class GeminiPrompt
 * Handles the generation and management of content for the Gemini system.
 *
 * @package RPurinton\GeminiPHP
 */
class GeminiPrompt
{
    private const ROLE_USER = 'user';
    private const ROLE_ASSISTANT = 'assistant';

    /**
     * @var array Configuration for content generation.
     */
    private array $generationConfig;

    /**
     * @var array Base contents that are used to reset the current contents.
     */
    private array $baseContents;

    /**
     * @var array System instruction for content generation.
     */
    private array $systemInstruction;

    /**
     * @var array Current contents that are being managed.
     */
    private array $contents;

    /**
     * @var array Safety settings for content generation.
     */
    private array $safetySettings;

    /**
     * @var array Tools used in content generation.
     */
    private array $tools;

    /**
     * @var Encoder Encoder instance for tokenizing text.
     */
    private Encoder $encoder;

    /**
     * Constructor for the GeminiPrompt class.
     * Initializes the class properties with provided configuration.
     *
     * @param array $config Configuration array for initializing the class properties.
     */
    public function __construct(array $config)
    {
        $this->generationConfig = $config['generation_config'] ?? [];
        $this->systemInstruction = $config['systemInstruction'] ?? [];
        $this->baseContents = $config['contents'] ?? [];
        $this->contents = $config['contents'] ?? [];
        $this->safetySettings = $config['safety_settings'] ?? [];
        $this->tools = $config['tools'] ?? [];
        $this->encoder = new Encoder();
        $this->validate();
    }

    /**
     * Sets the entire contents array.
     *
     * @param array $contents The contents array to be set.
     * @return bool Returns true on successful setting.
     * @throws \Exception If content validation fails.
     */
    public function setContents(array $contents): bool
    {
        Validate::contents($contents) or throw new \Exception("Error: Content validation failed for contents: " . json_encode($contents));
        $this->contents = $contents;
        return true;
    }

    /**
     * Adds multiple messages to the contents array.
     *
     * @param array $contents The contents to be added.
     * @return bool Returns true on successful addition.
     * @throws \Exception If content validation fails.
     */
    public function pushMultiple(array $contents): bool
    {
        $possibleContents = array_merge($this->contents, $contents);
        Validate::contents($possibleContents) or throw new \Exception("Error: Content validation failed for contents: " . json_encode($contents));
        $this->contents = $possibleContents;
        return true;
    }

    /**
     * Adds new a new single message to the contents array.
     *
     * @param array $content The content to be added.
     * @return bool Returns true on successful addition.
     * @throws \Exception If content validation fails.
     */
    public function push(array $content): bool
    {
        $possibleContents = array_merge($this->contents, [$content]);
        Validate::contents($possibleContents) or throw new \Exception("Error: Content validation failed for content: " . json_encode($content));
        $this->contents[] = $content;
        return true;
    }

    /**
     * Adds a message with a specified role to the current contents.
     *
     * @param string $role The role of the message sender.
     * @param string $content The message content.
     * @return bool Returns true on successful addition.
     */
    public function pushMessage(string $role, string $content): bool
    {
        return $this->push(['role' => $role, 'parts' => [['text' => $content]]]);
    }

    /**
     * Adds a user message to the current contents.
     *
     * @param string $content The user message content.
     * @return bool Returns true on successful addition.
     */
    public function pushUser(string $content): bool
    {
        return $this->pushMessage(self::ROLE_USER, $content);
    }

    /**
     * Adds an assistant message to the current contents.
     *
     * @param string $content The assistant message content.
     * @return bool Returns true on successful addition.
     */
    public function pushAssistant(string $content): bool
    {
        return $this->pushMessage(self::ROLE_ASSISTANT, $content);
    }

    /**
     * Resets the current contents to the base contents.
     *
     * @throws \Exception If base contents validation fails.
     */
    public function resetContent(): void
    {
        Validate::contents($this->baseContents) or throw new \Exception("Error: Base contents validation failed for content: " . json_encode($this->baseContents));
        $this->contents = $this->baseContents;
    }

    /**
     * Counts the number of tokens in a given text.
     *
     * @param string $text The text to be tokenized.
     * @return int The number of tokens.
     */
    public function tokenCount(string $text): int
    {
        return count($this->encoder->encode($text));
    }

    /**
     * Converts the current state of the object to JSON.
     *
     * @return string JSON representation of the object.
     */
    public function toJson(): string
    {
        $this->validate();
        return json_encode([
            //'systemInstruction' => $this->systemInstruction,
            'contents' => $this->contents,
            'tools' => $this->tools,
            'safetySettings' => $this->safetySettings,
            'generationConfig' => $this->generationConfig
        ]);
    }

    /**
     * Magic method to convert the object to a string.
     *
     * @return string JSON representation of the object.
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Validates the current state of the object.
     *
     * @return bool Returns true if validation passes.
     * @throws \Exception If any validation fails.
     */
    private function validate(): bool
    {
        Validate::contents($this->contents) or throw new \Exception("Error: Content validation failed for contents: " . json_encode($this->contents));
        Validate::tools($this->tools) or throw new \Exception("Error: Tools validation failed for tools: " . json_encode($this->tools));
        Validate::safetySettings($this->safetySettings) or throw new \Exception("Error: Safety settings validation failed for settings: " . json_encode($this->safetySettings));
        Validate::generationConfig($this->generationConfig) or throw new \Exception("Error: Generation config validation failed for config: " . json_encode($this->generationConfig));
        return true;
    }
}
