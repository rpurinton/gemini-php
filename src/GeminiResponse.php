<?php

namespace RPurinton\GeminiPHP;

class GeminiResponse
{
    public function __construct(private ?array $response)
    {
        // Initialize the response with candidates and usage metadata
    }

    public function getText(): string
    {
        $text = '';
        foreach ($this->response as $candidate)
            foreach ($candidate['candidates'] as $candidate2)
                if (isset($candidate2['content']['parts']))
                    foreach ($candidate2['content']['parts'] as $part)
                        $text .= $part['text'];

        if (empty($text)) return '<censored>';
        return $text;
    }

    public function getUsageMetadata(): ?array
    {
        if (!$this->response) return null;
        return end($this->response)['usageMetadata'];
    }
}
