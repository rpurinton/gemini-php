<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\GeminiPHP\HTTPClient;

class HTTPClientTest extends TestCase
{
    public function testPost(): void
    {
        $this->expectException(\Exception::class);
        HTTPClient::post('https://invalid.url', [], 'data');
    }
}
