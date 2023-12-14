<?php

namespace RPurinton\GeminiPHP;

class GeminiResponse
{
    public function __construct(private array $candidates, private array $usageMetadata)
    {
        // Initialize the response with candidates and usage metadata
    }

    public function getCandidates()
    {
        return $this->candidates;
    }

    public function getUsageMetadata()
    {
        return $this->usageMetadata;
    }
}
