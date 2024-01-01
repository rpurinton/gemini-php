<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\GeminiPHP\GeminiClient;

class GeminiClientTest extends TestCase
{
    public function testInvalidCredentials(): void
    {
        $this->expectException(\Exception::class);
        $client = new GeminiClient(['credentialsPath' => 'invalid_path.json']);
        $client->refreshAccessToken();
    }
}
