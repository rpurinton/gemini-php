<?php

namespace RPurinton\GeminiPHP;

class HTTPClient
{
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
