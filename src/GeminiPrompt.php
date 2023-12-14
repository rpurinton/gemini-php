<?php

namespace RPurinton\GeminiPHP;

class GeminiPrompt
{
    public function __construct(
        private array $generationConfig,
        private array $contents,
        private array $tools = [],
        private array $safetySettings = []
    ) {
        // Initialize the prompt with contents, tools, safety settings, and generation config
    }

    public function toJson()
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
