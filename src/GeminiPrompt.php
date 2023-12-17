<?php

namespace RPurinton\GeminiPHP;

class GeminiPrompt
{
    private array $generationConfig;
    private array $content;
    private array $safetySettings;
    private array $tools;

    public function __construct(array $dependencies)
    {
        $this->generationConfig = $dependencies['generation_config'];
        $this->content = $dependencies['content'];
        $this->safetySettings = $dependencies['safety_settings'] ?? [];
        $this->tools = $dependencies['tools'] ?? [];
    }

    public function push(array $new_content): void
    {
        $this->content[] = $new_content;
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
