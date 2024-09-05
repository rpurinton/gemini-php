<?php

use PHPUnit\Framework\TestCase;
use RPurinton\GeminiPHP\Validate;

class ValidateTest extends TestCase
{
    public function testClientConfig()
    {
        $valid_config = [
            'projectId' => 'test_project',
            'regionName' => 'us-central1',
            'credentialsPath' => __DIR__ . '/test_configs/service_account.json',
            'modelName' => 'gemini-1.4-flash'
        ];

        $this->assertTrue(Validate::clientConfig($valid_config));

        $invalid_config = [
            'projectId' => 'test_project',
            'regionName' => 'invalid_region',
            'credentialsPath' => '/not/a/path/to/credentials.json',
            'modelName' => 'gemini-fake-model'
        ];

        $this->expectException(\Exception::class);
        Validate::clientConfig($invalid_config);
    }

    public function testCredentials()
    {
        $valid_credentials_path = __DIR__ . '/test_configs/service_account.json';
        $this->assertTrue(Validate::credentials($valid_credentials_path));

        $invalid_credentials_path = '/path/to/invalid_credentials.json';
        $this->expectException(\Exception::class);
        Validate::credentials($invalid_credentials_path);
    }

    public function testContents()
    {
        $valid_contents = [
            ['role' => 'user', 'parts' => ['text' => 'Hello']],
            ['role' => 'assistant', 'parts' => ['text' => 'Hello']]
        ];
        $this->assertTrue(Validate::contents($valid_contents));

        $invalid_contents = [
            ['role' => 'user', 'parts' => ['Hello']],
            ['role' => 'user', 'parts' => ['Hello']]
        ];
        $this->expectException(\Exception::class);
        Validate::contents($invalid_contents);
    }

    public function testGenerationConfig()
    {
        $valid_config = [
            'temperature' => 0.5,
            'topP' => 0.5,
            'topK' => 20,
            'maxOutputTokens' => 1024
        ];
        $this->assertTrue(Validate::generationConfig($valid_config));

        $invalid_config = [
            'temperature' => 2,
            'topP' => 0.5,
            'topK' => 20,
            'maxOutputTokens' => 1024
        ];
        $this->expectException(\Exception::class);
        Validate::generationConfig($invalid_config);
    }

    public function testSafetySettings()
    {
        $valid_settings = [
            ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE']
        ];
        $this->assertTrue(Validate::safetySettings($valid_settings));

        $invalid_settings = [
            ['category' => 'INVALID_CATEGORY', 'threshold' => 'BLOCK_NONE']
        ];
        $this->expectException(\Exception::class);
        Validate::safetySettings($invalid_settings);
    }

    public function testTools()
    {
        $valid_tools = [
            [
                'function_declarations' => [
                    [
                        'name' => 'test',
                        'description' => 'test function',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                ['type' => 'string', 'description' => 'test parameter']
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->assertTrue(Validate::tools($valid_tools));

        $invalid_tools = [
            [
                'function_declarations' => [
                    [
                        'name' => 'test',
                        'description' => 'test function',
                        'parameters' => [
                            'type' => 'object',
                            'properties' => [
                                ['type' => 'invalid_type', 'description' => 'test parameter']
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->expectException(\Exception::class);
        Validate::tools($invalid_tools);
    }
}
