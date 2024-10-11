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
     * @param array $options Additional options for the request (e.g., timeout).
     * @return string The response from the server.
     * @throws \RuntimeException If the HTTP request times out.
     * @throws \Exception If the HTTP request fails for other reasons.
     */
    public static function post(string $url, array $headers = [], string $data = '', array $options = []): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        // Apply timeout options if provided
        if (isset($options['timeout'])) {
            $timeout = $options['timeout'];
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        }
        if (isset($options['connect_timeout'])) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $options['connect_timeout']);
        }

        $start = microtime(true);
        $mh = curl_multi_init();
        curl_multi_add_handle($mh, $ch);

        $active = null;
        $response = '';

        do {
            $status = curl_multi_exec($mh, $active);
            $info = curl_multi_info_read($mh);

            if ($info !== false) {
                $response = curl_multi_getcontent($ch);
            }

            if (isset($timeout) && microtime(true) - $start > $timeout) {
                curl_multi_remove_handle($mh, $ch);
                curl_multi_close($mh);
                throw new \RuntimeException("Operation timed out after $timeout seconds");
            }

            if ($active) {
                curl_multi_select($mh, 0.1);
            }

        } while ($active && $status == CURLM_OK);

        curl_multi_remove_handle($mh, $ch);
        curl_multi_close($mh);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            
            if ($errno == CURLE_OPERATION_TIMEOUTED) {
                throw new \RuntimeException("HTTP request timed out: $error");
            } else {
                throw new \Exception("HTTP request failed: $error");
            }
        }

        curl_close($ch);

        if ($response === false) {
            throw new \RuntimeException("Failed to get response");
        }

        return $response;
    }
}
