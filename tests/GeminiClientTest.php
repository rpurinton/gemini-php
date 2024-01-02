<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\GeminiPHP\GeminiClient;

class GeminiClientTest extends TestCase
{
    public function testConstructorWithInvalidProjectId(): void
    {
        $this->expectException(\Exception::class);
        new GeminiClient(['projectId' => '']);
    }

    public function testConstructorWithInvalidRegionName(): void
    {
        $this->expectException(\Exception::class);
        new GeminiClient(['projectId' => 'ai-project-123456', 'regionName' => '']);
    }

    public function testConstructorWithInvalidCredentialsPath(): void
    {
        $this->expectException(\Exception::class);
        new GeminiClient(['projectId' => 'ai-project-123456', 'regionName' => 'us-east4', 'credentialsPath' => '']);
    }

    public function testConstructorWithInvalidModelName(): void
    {
        $this->expectException(\Exception::class);
        new GeminiClient(['projectId' => 'ai-project-123456', 'regionName' => 'us-east4', 'credentialsPath' => 'valid_path.json', 'modelName' => '']);
    }
}
