<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\GeminiPHP\GeminiResponse;

class GeminiResponseTest extends TestCase
{
    public function testGetText(): void
    {
        $response = new GeminiResponse([['candidates' => [['content' => ['parts' => [['text' => 'Test response']]]]]]]);
        $this->assertEquals('Test response', $response->getText());
    }

    public function testGetUsageMetadata(): void
    {
        $response = new GeminiResponse([['usageMetadata' => ['characters' => 100]]]);
        $this->assertIsArray($response->getUsageMetadata());
        $this->assertEquals(['characters' => 100], $response->getUsageMetadata());
    }
}
