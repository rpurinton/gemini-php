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
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('HTTP request failed: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}
