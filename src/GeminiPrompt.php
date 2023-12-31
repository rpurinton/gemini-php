<?php

namespace RPurinton\GeminiPHP;

use TikToken\Encoder;

class GeminiPrompt
{
    private ?array $generation_config;
    private ?array $contents;
    private ?array $safety_settings;
    private ?array $tools;
    private ?Encoder $encoder;

    public function __construct(array $config)
    {
        $this->generation_config = $config['generation_config'];
        $this->contents = $config['contents'];
        $this->safety_settings = $config['safety_settings'] ?? [];
        $this->tools = $config['tools'] ?? [];
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
        return json_encode([
            'contents' => $this->contents,
            'tools' => $this->tools,
            'safety_settings' => $this->safety_settings,
            'generation_config' => $this->generation_config
        ]);
    }
}
