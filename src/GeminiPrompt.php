<?php

namespace RPurinton\GeminiPHP;

class GeminiPrompt
{
    private $contents;
    private $tools;
    private $safetySettings;
    private $generationConfig;

    public function __construct($contents, $tools, $safetySettings, $generationConfig)
    {
        // Initialize the prompt with contents, tools, safety settings, and generation config
    }

    public function toJson()
    {
        // Convert the prompt data to JSON format for the API request
    }
}
