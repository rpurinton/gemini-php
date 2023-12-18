<?php

namespace RPurinton\GeminiPHP;

use TikToken\Encoder;

class GeminiPrompt
{
    private ?array $generationConfig;
    private ?array $contents;
    private ?array $safetySettings;
    private ?array $tools;
    private ?Encoder $encoder;

    public function __construct(array $dependencies)
    {
        $this->generationConfig = $dependencies['generation_config'];
        $this->contents = $dependencies['contents'];
        $this->safetySettings = $dependencies['safety_settings'] ?? [];
        $this->tools = $dependencies['tools'] ?? [];
        $this->encoder = new Encoder();
    }

    public function push(array $new_content): void
    {
        $this->contents[] = $new_content;
    }

    public function setContent(array $new_content): void
    {
        $this->contents = $new_content;
    }

    public function token_count($text)
    {
        return count($this->encoder->encode($text));
    }

    public function toJson(): string
    {
        // Convert the prompt to JSON
        return json_encode([
            'contents' => $this->contents,
            'tools' => $this->tools,
            'safety_settings' => $this->safetySettings,
            'generation_config' => $this->generationConfig
        ]);
    }
}
