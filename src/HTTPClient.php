<?php

namespace RPurinton\GeminiPHP;

class HTTPClient
{
    public static function get($url, $headers = [])
    {
        return file_get_contents($url, false, stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => $headers
            ]
        ]));
    }

    public static function post($url, $data, $headers = [])
    {
        return file_get_contents($url, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $headers,
                'content' => $data
            ]
        ]));
    }
}
