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
    private ?bool $streamContent = true;
    private ?int $timeout = null; // Default to no timeout

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
        $this->streamContent = isset($config['streamContent']) ? $config['streamContent'] : true;
        $this->timeout = isset($config['timeout']) ? $config['timeout'] : null;
    }

    /**
     * Gets the response.
     *
     * @param string $promptData A JSON string representing the prompt data. This should be the output of the GeminiPrompt->toJson() method.
     * @param int|null $timeout Optional timeout in seconds. If not provided, uses the default timeout (which may be null).
     * @return GeminiResponse The response from the Gemini API.
     * @throws \Exception If the response is not valid JSON, if the response is empty, or if the request times out.
     */
    public function getResponse(string $promptData, ?int $timeout = null): GeminiResponse
    {
        $retryCount = 0;
        $maxRetries = 3;
        $timeout = $timeout ?? $this->timeout;
        $start = microtime(true);

        while ($retryCount < $maxRetries) {
            try {
                // Check if we've exceeded the timeout (only if a timeout is set)
                if ($timeout !== null && microtime(true) - $start > $timeout) {
                    throw new \RuntimeException("Operation timed out after {$timeout} seconds.");
                }

                $accessToken = $this->tokenManager->getAccessToken();

                // Set options for this specific HTTP request
                $options = [];
                if ($timeout !== null) {
                    $options['timeout'] = $timeout - (microtime(true) - $start);
                    $options['connect_timeout'] = min(5, $options['timeout']);
                }

                $response_json = HTTPClient::post(
                    $this->buildUrl(),
                    $this->buildHeaders($accessToken),
                    $promptData,
                    $options
                );

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
                if ($retryCount >= $maxRetries || $e instanceof \RuntimeException) {
                    throw $e;
                }
                // Add a small delay before retrying
                usleep(100000 * $retryCount); // 0.1 seconds * retry count
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
            ($this->streamContent ? ':streamGenerateContent' : ':generateContent');
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
