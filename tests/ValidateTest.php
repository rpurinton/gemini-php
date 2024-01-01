<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RPurinton\GeminiPHP\Validate;

class ValidateTest extends TestCase
{
    public function testContents(): void
    {
        $this->assertTrue(Validate::contents([['role' => 'user', 'parts' => [['text' => 'Hello']]], ['role' => 'assistant', 'parts' => [['text' => 'Hi']]]]));
    }

    public function testGenerationConfig(): void
    {
        $this->assertTrue(Validate::generationConfig(['temperature' => 0.5, 'topP' => 0.5, 'topK' => 20, 'maxOutputTokens' => 1024]));
    }

    public function testSafetySettings(): void
    {
        $this->assertTrue(Validate::safetySettings([['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_LOW_AND_ABOVE']]));
    }

    public function testTools(): void
    {
        $this->assertTrue(Validate::tools([['function_declarations' => [['name' => 'testFunction', 'description' => 'A test function', 'parameters' => ['type' => 'object', 'properties' => [['type' => 'string', 'description' => 'A test parameter']]]]]]]));
    }
}
