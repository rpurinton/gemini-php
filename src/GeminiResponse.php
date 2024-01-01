<?php

namespace RPurinton\GeminiPHP;

/**
 * Class GeminiResponse
 * @package RPurinton\GeminiPHP
 */
class GeminiResponse
{
    /**
     * GeminiResponse constructor.
     * @param array|null $response
     */
    public function __construct(private ?array $response)
    {
        // Initialize the response with candidates and usage metadata
    }

    /**
     * Returns the text from the response.
     * @return string
     * @throws \Exception
     */
    public function getText(): string
    {
        $text = '';
        try {
            foreach ($this->response as $candidate)
                foreach ($candidate['candidates'] as $candidate2)
                    if (isset($candidate2['content']['parts']))
                        foreach ($candidate2['content']['parts'] as $part)
                            $text .= $part['text'];
        } catch (\Exception $e) {
            throw new \Exception('Error: Unable to parse response. ' . $e->getMessage());
        } finally {
            if (empty($text)) return '<censored>';
            return $text;
        }
    }

    /**
     * Returns the usage metadata from the response.
     * @return array|null
     */
    public function getUsageMetadata(): ?array
    {
        if (!$this->response) return null;
        return end($this->response)['usageMetadata'];
    }
}
