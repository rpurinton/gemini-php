<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\GeminiPHP\GeminiPrompt;

class GeminiPromptTest extends TestCase
{
    public function testPush(): void
    {
        $prompt = new GeminiPrompt([
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
        ]);
        $prompt->push(['role' => 'user', 'parts' => [['text' => 'Test push']]]);
        $this->assertNotEmpty($prompt->toJson());
    }

    public function testSetContent(): void
    {
        $prompt = new GeminiPrompt([
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
        ]);
        $prompt->setContent([['role' => 'user', 'parts' => [['text' => 'Test content']]]]);
        $this->assertNotEmpty($prompt->toJson());
    }

    public function testSetTools(): void
    {
        $prompt = new GeminiPrompt([
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
        ]);
        $prompt->setTools([['function_declarations' => [['name' => 'testTool', 'description' => 'A test tool', 'parameters' => ['type' => 'object', 'properties' => [['type' => 'string', 'description' => 'A test parameter']]]]]]]);
        $this->assertNotEmpty($prompt->toJson());
    }

    public function testSetSafetySettings(): void
    {
        $prompt = new GeminiPrompt([
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
        ]);
        $prompt->setSafetySettings([['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_LOW_AND_ABOVE']]);
        $this->assertNotEmpty($prompt->toJson());
    }

    public function testSetGenerationConfig(): void
    {
        $prompt = new GeminiPrompt([
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
        ]);
        $prompt->setGenerationConfig([
            'temperature' => 1.0,
            'topP' => 1.0,
            'topK' => 40,
            'maxOutputTokens' => 2048,
        ]);
        $this->assertNotEmpty($prompt->toJson());
    }
}
