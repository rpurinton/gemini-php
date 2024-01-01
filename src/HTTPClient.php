<?php

namespace RPurinton\GeminiPHP;

/**
 * Class HTTPClient
 * @package RPurinton\GeminiPHP
 */
class HTTPClient
{
    /**
     * Sends a POST request to the specified URL with the given headers and data.
     * @param string $url The URL to send the request to.
     * @param array $headers The headers to include in the request.
     * @param string $data The data to send in the body of the request.
     * @return string The response from the server.
     * @throws \Exception If the HTTP request fails.
     */
    public static function post(string $url, array $headers = [], string $data = ''): string
    {
        $response = @file_get_contents($url, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $headers,
                'content' => $data
            ]
        ]));

        if ($response === FALSE) {
            throw new \Exception('HTTP request failed: ' . error_get_last()['message']);
        }

        return $response;
    }
}
