<?php

use PHPUnit\Framework\TestCase;
use RPurinton\GeminiPHP\GeminiPrompt;

final class GeminiPromptTest extends TestCase
{
    private array $config = [
        'generation_config' => [
            'temperature' => 1.0,
            'topP' => 1.0,
            'topK' => 40,
            'maxOutputTokens' => 2048,
        ],
        'contents' => [
            [
                'role' => 'user',
                'parts' => [['text' => 'You are a helpful assistant.']],
            ],
            [
                'role' => 'assistant',
                'parts' => [['text' => 'I am a helpful assistant!']],
            ],
        ],
        'safety_settings' => [
            [
                'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                'threshold' => 'BLOCK_LOW_AND_ABOVE',
            ],
        ],
        'tools' => [],
    ];

    public function testGeminiPromptCanBeInstantiated(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $this->assertInstanceOf(GeminiPrompt::class, $prompt);
    }

    public function testPush(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $content = [
            'role' => 'user',
            'parts' => [['text' => 'New message']],
        ];
        $this->assertTrue($prompt->push($content));
    }

    public function testPushWithInvalidContent(): void
    {
        $this->expectException(\Exception::class);
        $prompt = new GeminiPrompt($this->config);
        $invalidContent = [
            'role' => 'invalid_role',
            'parts' => [['text' => 'New message']],
        ];
        $prompt->push($invalidContent);
    }

    public function testPushMessage(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $this->assertTrue($prompt->pushMessage('user', 'New message'));
    }

    public function testPushUser(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $this->assertTrue($prompt->pushUser('New message'));
    }

    public function testPushAssistant(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $prompt->pushUser('New message');
        $this->assertTrue($prompt->pushAssistant('New message'));
    }

    public function testResetContent(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $prompt->pushUser('New message');
        $prompt->resetContent();
        $result = json_decode($prompt->toJson(), true);
        $this->assertEquals($this->config['contents'], $result['contents']);
    }

    public function testTokenCount(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $this->assertEquals(4, $prompt->tokenCount('Hello, world!'));
    }

    public function testToJson(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $this->assertJson($prompt->toJson());
    }

    public function testToString(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $this->assertJson((string) $prompt);
    }

    public function testToJsonWithInvalidContents(): void
    {
        $this->expectException(\Exception::class);
        $invalidConfig = $this->config;
        $invalidConfig['contents'] = [
            [
                'role' => 'invalid_role',
                'parts' => [['text' => 'Invalid content']],
            ],
        ];
        $prompt = new GeminiPrompt($invalidConfig);
        $prompt->toJson();
    }

    public function testToStringWithInvalidContents(): void
    {
        $this->expectException(\Exception::class);
        $invalidConfig = $this->config;
        $invalidConfig['contents'] = [
            [
                'role' => 'invalid_role',
                'stuff' => [['content' => 'Invalid content']],
            ],
        ];
        $prompt = new GeminiPrompt($invalidConfig);
        (string) $prompt;
    }

    public function testValidateWithInvalidContents(): void
    {
        $this->expectException(\Exception::class);
        $invalidConfig = $this->config;
        $invalidConfig['contents'] = [
            [
                'role' => 'invalid_role',
                'stuff' => [['stuff' => 'Invalid content']],
            ],
        ];
        $prompt = new GeminiPrompt($invalidConfig);
    }

    public function testValidateWithValidContents(): void
    {
        $prompt = new GeminiPrompt($this->config);
        $reflection = new \ReflectionClass($prompt);
        $method = $reflection->getMethod('validate');
        $method->setAccessible(true);
        $this->assertTrue($method->invoke($prompt));
    }
}
