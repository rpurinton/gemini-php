<?php

namespace RPurinton\GeminiPHP;

class GeminiPrompt
{
    private array $generationConfig;
    private array $contents;
    private array $safetySettings;
    private array $tools;

    public function __construct(array $dependencies)
    {
        $this->generationConfig = $dependencies['generationConfig'];
        $this->contents = $dependencies['contents'];
        $this->safetySettings = $dependencies['safetySettings'] ?? [];
        $this->tools = $dependencies['tools'] ?? [];
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
