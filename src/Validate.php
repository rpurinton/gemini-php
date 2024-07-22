<?php

namespace RPurinton\GeminiPHP;

/**
 * Class Validate
 *
 * This class provides static methods for validating various aspects of the GeminiPrompt object.
 * It includes methods for validating the contents, generation configuration, safety settings, and tools.
 *
 * @package RPurinton\GeminiPHP
 */
class Validate
{
    const VALID_REGIONS = [
        'us-central1', // Iowa
        'us-west4', // Las Vegas, Nevada
        'northamerica-northeast1', // Montréal, Canada
        'us-east4', // Northern Virginia
        'us-west1', // Oregon
        'asia-northeast3', // Seoul, Korea
        'asia-southeast1', // Singapore
        'asia-northeast1' // Tokyo, Japan
    ];

    const VALID_MODELS = [
        'gemini-pro', // 32k token model (text + function calling)
        'gemini-pro-vision', // 16k multi-modal model (text + images + video + function calling)
        'gemini-1.5-pro-001',
        'gemini-1.5-flash-001',
        'gemini-1.0-pro-vision-001',
        'gemini-experimental',
    ];

    const VALID_CATEGORIES = [
        'HARM_CATEGORY_SEXUALLY_EXPLICIT',
        'HARM_CATEGORY_HATE_SPEECH',
        'HARM_CATEGORY_HARASSMENT',
        'HARM_CATEGORY_DANGEROUS_CONTENT'
    ];

    const VALID_THRESHOLDS = [
        'BLOCK_NONE',
        'BLOCK_LOW_AND_ABOVE',
        'BLOCK_MED_AND_ABOVE',
        'BLOCK_HIGH_AND_ABOVE'
    ];

    const VALID_PROPERTY_TYPES = [
        'string',
        'number',
        'integer',
        'boolean',
        'array',
        'object',
        'null'
    ];

    const VALID_ROLES = [
        'user',
        'assistant'
    ];

    /**
     * Validates the provided client configuration.
     *
     * @param mixed $client_config The client configuration to validate.
     * @return bool Returns true if validation passes, throws an exception otherwise.
     * @throws \Exception If the client config is not set, not an array, or if any setting is invalid.
     */
    public static function clientConfig(mixed $client_config): bool
    {
        $required_keys = ['projectId', 'regionName', 'credentialsPath', 'modelName'];
        $allowed_keys = ['ignoreModelValidation', 'ignoreRegionValidation', 'streamContent'];
    
        $actual_keys = array_keys($client_config);
    
        // Check if all required keys are present
        if (count(array_intersect($required_keys, $actual_keys)) !== count($required_keys)) {
            throw new \Exception('Error: Missing required keys in client config.');
        }
    
        // Check if all actual keys are either required or allowed
        if (count(array_diff($actual_keys, array_merge($required_keys, $allowed_keys))) > 0) {
            throw new \Exception('Error: Unexpected keys found in client config.');
        }

        if (!isset($client_config)) throw new \Exception('Error: Client config not set.');
        if (!is_array($client_config)) throw new \Exception('Error: Client config must be an array.');

        if (!is_string($client_config['projectId'])) throw new \Exception('Error: projectId must be a string.');
        if (!is_string($client_config['regionName'])) throw new \Exception('Error: regionName must be a string.');
        if (!isset($client_config['ignoreRegionValidation']) || $client_config['ignoreRegionValidation'] !== 'true') {
            if (!in_array($client_config['regionName'], self::VALID_REGIONS)) {
                throw new \Exception('Error: Invalid regionName in client config.');
            }
        }
        if (!is_string($client_config['credentialsPath'])) throw new \Exception('Error: credentialsPath must be a string.');
        if (!self::credentials($client_config['credentialsPath'])) throw new \Exception('Error: Invalid credentialsPath in client config.');
        if (!is_string($client_config['modelName'])) throw new \Exception('Error: modelName must be a string.');
        if (!isset($client_config['ignoreModelValidation']) || $client_config['ignoreModelValidation'] !== 'true') {
            if (!in_array($client_config['modelName'], self::VALID_MODELS)) {
                throw new \Exception('Error: Invalid modelName in client config.');
            }
        }
        return true;
    }

    /**
     * Validates the credentials.
     *
     * @param mixed $credentialsPath The path to the credentials file.
     * @return bool Returns true if validation passes, throws an exception otherwise.
     * @throws \Exception If the credentials file is not found, not readable, or if the contents cannot be parsed.
     */
    public static function credentials(mixed $credentialsPath): bool
    {
        if (!file_exists($credentialsPath)) throw new \Exception('Error: Credentials file not found.');
        if (!is_readable($credentialsPath)) throw new \Exception('Error: Credentials file not readable.');
        $contents = file_get_contents($credentialsPath) or throw new \Exception('Error: Unable to read credentials file.');
        $json = json_decode($contents, true) or throw new \Exception('Error: Unable to parse credentials file.');

        $requiredKeys = [
            'type', 'project_id', 'private_key_id', 'private_key', 'client_email',
            'client_id', 'auth_uri', 'token_uri', 'auth_provider_x509_cert_url',
            'client_x509_cert_url', 'universe_domain'
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($json[$key])) throw new \Exception("Error: Credentials file missing {$key}.");
        }

        if ($json['type'] !== 'service_account') throw new \Exception('Error: Credentials file type must be service_account.');

        return true;
    }

    /**
     * Validates the contents of the provided array.
     *
     * @param mixed $contents The contents to validate.
     * @return bool Returns true if validation passes, throws an exception otherwise.
     * @throws \Exception If the contents are not set, not an array, empty, or if the roles do not alternate between "user" and "assistant".
     */
    public static function contents(mixed $contents): bool
    {
        if (!isset($contents)) throw new \Exception('Error: Contents not set.');
        if (!is_array($contents)) throw new \Exception('Error: Contents must be an array.');
        if (!count($contents)) throw new \Exception('Error: Contents must not be empty.');
        $last_role = null;
        foreach ($contents as $content) {
            if (!isset($content['role'])) throw new \Exception('Error: Content role not set.');
            if (!is_string($content['role'])) throw new \Exception('Error: Content role must be a string.');
            if (!in_array($content['role'], self::VALID_ROLES)) throw new \Exception('Error: Content role must be either "user" or "assistant".');
            if (!isset($content['parts'])) throw new \Exception('Error: Content parts not set.');
            if (!is_array($content['parts'])) throw new \Exception('Error: Content parts must be an array.');
            if (!count($content['parts'])) throw new \Exception('Error: Content parts must not be empty.');
            if (!$last_role && $content['role'] !== 'user') throw new \Exception('Error: First content role must be "user".');
            if ($content['role'] === $last_role) throw new \Exception('Error: Content roles must alternate between "user" and "assistant".');
            $last_role = $content['role'];
        }
        return true;
    }

    /**
     * Validates the provided generation configuration.
     *
     * @param mixed $generation_config The generation configuration to validate.
     * @return bool Returns true if validation passes, throws an exception otherwise.
     * @throws \Exception If the generation config is not set, not an array, or if the keys do not match the expected keys.
     */
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

        if ($generation_config['maxOutputTokens'] > 8192 || $generation_config['maxOutputTokens'] < 0) {
            throw new \Exception('Error: maxOutputTokens must be between 0 and 8192.');
        }

        return true;
    }

    /**
     * Validates the provided safety settings.
     *
     * @param mixed $safety_settings The safety settings to validate.
     * @return bool Returns true if validation passes, throws an exception otherwise.
     * @throws \Exception If the safety settings are not set, not an array, or if any setting is invalid.
     */
    public static function safetySettings(mixed $safety_settings): bool
    {
        if (!isset($safety_settings)) throw new \Exception('Error: Safety settings not set.');
        if (!is_array($safety_settings)) throw new \Exception('Error: Safety settings must be an array.');

        foreach ($safety_settings as $setting) {
            if (!is_array($setting)) throw new \Exception('Error: Each safety setting must be an array.');
            if (!isset($setting['category']) || !isset($setting['threshold'])) {
                throw new \Exception('Error: Each safety setting must contain a category and a threshold.');
            }

            if (!in_array($setting['category'], self::VALID_CATEGORIES)) {
                throw new \Exception('Error: Invalid category in safety settings.');
            }

            if (!in_array($setting['threshold'], self::VALID_THRESHOLDS)) {
                throw new \Exception('Error: Invalid threshold in safety settings.');
            }
        }

        return true;
    }

    /**
     * Validates the provided tools.
     *
     * @param mixed $tools The tools to validate.
     * @return bool Returns true if validation passes, throws an exception otherwise.
     * @throws \Exception If the tools are not set, not an array, or if any tool is invalid.
     */
    public static function tools(mixed $tools): bool
    {
        if (!isset($tools)) throw new \Exception('Error: Tools not set.');
        if (!is_array($tools)) throw new \Exception('Error: Tools must be an array.');

        if ($tools === []) return true;

        if (count($tools) > 1) throw new \Exception('Error: Only one set of function_declarations is currently supported.');
        $tool = $tools[0];
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
                if (!in_array($property['type'], self::VALID_PROPERTY_TYPES)) {
                    throw new \Exception('Error: Invalid property type {$property["type"]} in {$functionDeclaration["name"]} function declaration.');
                }
            }

            if (isset($functionDeclaration['parameters']['required']) && (!is_array($functionDeclaration['parameters']['required']) || array_diff($functionDeclaration['parameters']['required'], array_keys($functionDeclaration['parameters']['properties'])))) {
                throw new \Exception('Error: If "required" is set, it must be an array of strings that are keys in "properties".');
            }
        }

        return true;
    }
}
