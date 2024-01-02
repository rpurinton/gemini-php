<?php

namespace RPurinton\GeminiPHP;

use Google\Auth\ApplicationDefaultCredentials;

/**
 * Class GeminiClient
 * @package RPurinton\GeminiPHP
 */
class GeminiClient
{
    private ?string $projectId;
    private ?string $regionName;
    private ?string $modelName;
    private AccessTokenManager $tokenManager;


    /**
     * GeminiClient constructor.
     *
     * Initializes a new instance of the GeminiClient with the provided configuration.
     * The configuration array must include the following keys:
     * - 'credentialsPath': The path to the file containing the API credentials.
     * - 'projectId': The ID of the project in the Gemini platform.
     * - 'regionName': The name of the region where the Gemini project is hosted.
     * - 'modelName': The name of the model to use for generation.
     *
     * @param array $config The configuration array.
     */
    public function __construct(private array $config)
    {
        Validate::clientConfig($config) or throw new \Exception('Error: Client configuration validation failed.');
        $this->projectId = $config['projectId'];
        $this->regionName = $config['regionName'];
        $this->modelName = $config['modelName'];
        $this->tokenManager = new AccessTokenManager($config['credentialsPath']);
    }

    /**
     * Gets the response.
     *
     * @param string $promptData A JSON string representing the prompt data. This should be the output of the GeminiPrompt->toJson() method.
     * @return GeminiResponse The response from the Gemini API.
     * @throws \Exception If the response is not valid JSON or if the response is empty.
     */
    public function getResponse(string $promptData): GeminiResponse
    {
        $retryCount = 0;
        while ($retryCount < 3) {
            try {
                $accessToken = $this->tokenManager->getAccessToken();
                $response_json = HTTPClient::post($this->buildUrl(), $this->buildHeaders($accessToken), $promptData);

                $response_json = json_decode($response_json, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Error: Response is not valid JSON. ' . json_last_error_msg());
                }
                if (!count($response_json)) {
                    throw new \Exception('Error: Response is empty.');
                }
                return new GeminiResponse($response_json);
            } catch (\Exception $e) {
                $retryCount++;
                if ($retryCount >= 3) {
                    throw $e;
                }
            }
        }
    }

    /**
     * Builds the URL.
     * @return string
     */
    private function buildUrl(): string
    {
        return 'https://' . $this->regionName .
            '-aiplatform.googleapis.com/v1' .
            '/projects/' . $this->projectId .
            '/locations/' . $this->regionName .
            '/publishers/google/models/' . $this->modelName .
            ':streamGenerateContent';
    }

    /**
     * Builds the headers.
     * @return array
     */
    private function buildHeaders(string $accessToken): array
    {
        return [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json; charset=utf-8',
        ];
    }
}
