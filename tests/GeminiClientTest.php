<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\GeminiPHP\GeminiClient;

class GeminiClientTest extends TestCase
{
    public function testValidateCredentials(): void
    {
        $this->expectException(\Exception::class);
        $client = new GeminiClient(['credentialsPath' => 'invalid_path.json']);
        $client->validateCredentials();
    }

    public function testGetResponse(): void
    {
        $this->expectException(\Exception::class);
        $client = new GeminiClient(['credentialsPath' => 'valid_path.json', 'projectId' => 'project_id', 'regionName' => 'region', 'modelName' => 'model']);
        $client->getResponse('{}');
    }
}
