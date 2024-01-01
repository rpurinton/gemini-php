<?php

namespace RPurinton\GeminiPHP;

class Validate
{
    public static function contents(mixed $contents): bool
    {
        if (!isset($contents)) throw new \Exception('Error: Contents not set.');
        if (!is_array($contents)) throw new \Exception('Error: Contents must be an array.');
        if (!count($contents)) throw new \Exception('Error: Contents must not be empty.');
        $last_role = null;
        foreach ($contents as $content) {
            if (!isset($content['role'])) throw new \Exception('Error: Content role not set.');
            if (!is_string($content['role'])) throw new \Exception('Error: Content role must be a string.');
            if (!in_array($content['role'], ['user', 'assistant'])) throw new \Exception('Error: Content role must be either "user" or "assistant".');
            if (!isset($content['parts'])) throw new \Exception('Error: Content parts not set.');
            if (!is_array($content['parts'])) throw new \Exception('Error: Content parts must be an array.');
            if (!count($content['parts'])) throw new \Exception('Error: Content parts must not be empty.');
            if ($content['role'] === $last_role) throw new \Exception('Error: Content roles must alternate between "user" and "assistant".');
            $last_role = $content['role'];
        }
        return true;
    }

    public static function generationConfig(mixed $generation_config): bool
    {
        if (!isset($generation_config)) throw new \Exception('Error: Generation config not set.');
        if (!is_array($generation_config)) throw new \Exception('Error: Generation config must be an array.');

        $expected_keys = ['temperature', 'topP', 'topK', 'maxOutputTokens'];
        $actual_keys = array_keys($generation_config);
        sort($expected_keys);
        sort($actual_keys);

        if ($expected_keys !== $actual_keys) {
            throw new \Exception('Error: Generation config keys do not match expected keys.');
        }

        if ($generation_config['temperature'] > 1.0 || $generation_config['temperature'] < 0) {
            throw new \Exception('Error: Temperature must be between 0 and 1.');
        }

        if ($generation_config['topP'] > 1.0 || $generation_config['topP'] < 0) {
            throw new \Exception('Error: topP must be between 0 and 1.');
        }

        if ($generation_config['topK'] > 40 || $generation_config['topK'] < 0) {
            throw new \Exception('Error: topK must be between 0 and 40.');
        }

        if ($generation_config['maxOutputTokens'] > 2048 || $generation_config['maxOutputTokens'] < 0) {
            throw new \Exception('Error: maxOutputTokens must be between 0 and 2048.');
        }

        return true;
    }

    public static function safetySettings(mixed $safety_settings): bool
    {
        $valid_categories = [
            'HARM_CATEGORY_SEXUALLY_EXPLICIT',
            'HARM_CATEGORY_HATE_SPEECH',
            'HARM_CATEGORY_HARASSMENT',
            'HARM_CATEGORY_DANGEROUS_CONTENT'
        ];

        $valid_thresholds = [
            'BLOCK_NONE',
            'BLOCK_LOW_AND_ABOVE',
            'BLOCK_MED_AND_ABOVE',
            'BLOCK_HIGH_AND_ABOVE'
        ];

        if (!isset($safety_settings)) throw new \Exception('Error: Safety settings not set.');
        if (!is_array($safety_settings)) throw new \Exception('Error: Safety settings must be an array.');

        foreach ($safety_settings as $setting) {
            if (!is_array($setting)) throw new \Exception('Error: Each safety setting must be an array.');
            if (!isset($setting['category']) || !isset($setting['threshold'])) {
                throw new \Exception('Error: Each safety setting must contain a category and a threshold.');
            }

            if (!in_array($setting['category'], $valid_categories)) {
                throw new \Exception('Error: Invalid category in safety settings.');
            }

            if (!in_array($setting['threshold'], $valid_thresholds)) {
                throw new \Exception('Error: Invalid threshold in safety settings.');
            }
        }

        return true;
    }

    public static function tools(mixed $tools): bool
    {
        if (!isset($tools)) throw new \Exception('Error: Tools not set.');
        if (!is_array($tools)) throw new \Exception('Error: Tools must be an array.');

        foreach ($tools as $tool) {
            if (!is_array($tool) || !isset($tool['function_declarations'])) {
                throw new \Exception('Error: Each tool must be an array with a "function_declarations" key.');
            }

            foreach ($tool['function_declarations'] as $functionDeclaration) {
                if (!is_array($functionDeclaration) || !isset($functionDeclaration['name']) || !isset($functionDeclaration['description']) || !isset($functionDeclaration['parameters'])) {
                    throw new \Exception('Error: Each function declaration must be an array with "name", "description", and "parameters" keys.');
                }

                if (!is_string($functionDeclaration['name']) || !is_string($functionDeclaration['description']) || !is_array($functionDeclaration['parameters'])) {
                    throw new \Exception('Error: "name" and "description" must be strings, "parameters" must be an array.');
                }

                if (!isset($functionDeclaration['parameters']['type']) || $functionDeclaration['parameters']['type'] !== 'object' || !isset($functionDeclaration['parameters']['properties']) || !is_array($functionDeclaration['parameters']['properties'])) {
                    throw new \Exception('Error: "parameters" must be an object with "type" set to "object" and "properties" as an array.');
                }

                foreach ($functionDeclaration['parameters']['properties'] as $property) {
                    if (!is_array($property) || !isset($property['type']) || !isset($property['description']) || !is_string($property['type']) || !is_string($property['description'])) {
                        throw new \Exception('Error: Each property in "properties" must be an array with "type" and "description" keys, both of which must be strings.');
                    }
                }

                if (isset($functionDeclaration['parameters']['required']) && (!is_array($functionDeclaration['parameters']['required']) || array_diff($functionDeclaration['parameters']['required'], array_keys($functionDeclaration['parameters']['properties'])))) {
                    throw new \Exception('Error: If "required" is set, it must be an array of strings that are keys in "properties".');
                }
            }
        }

        return true;
    }
}
