<?php

namespace SalimId\Telegraph;

use Exception as PhpException;
use GuzzleHttp\Client as Http;
use SalimId\Telegraph\Exception;

class Client
{
    /**
     * Telegraph API base URI.
     */
    const BASE_URI = 'https://api.telegra.ph/';

    /**
     * HTTP client.
     *
     * @var Http
     */
    protected static Http $http;

    /**
     * The token.
     *
     * @var string
     */
    protected static string $token;

    /**
     * Get HTTP client.
     *
     * @return Http
     */
    public static function http()
    {
        if (empty(self::$http)) {
            self::$http = new Http(['base_uri' => self::BASE_URI]);
        }

        return self::$http;
    }

    /**
     * Get/set token.
     *
     * @param string|null $token
     * @return string
     */
    public static function token(?string $token = null)
    {
        if (empty(self::$token)) {
            self::$token = $token;
        }

        return self::$token;
    }

    /**
     * Make a request.
     *
     * @param string $method
     * @param string $path
     * @param array<mixed> $options
     * @return array<mixed>|null
     */
    public static function request(string $method, string $path, array $options = []): ?array
    {
        if (isset(self::$token)) {
            $options['json']['access_token'] = self::$token;
        }

        try {
            $response = self::http()->request($method, $path, $options);
            $response = json_decode($response->getBody()->getContents(), true);

            if (!is_array($response) ||
                !isset($response['ok']) ||
                !$response['ok'] ||
                !isset($response['result'])
            ) {
                $error = 'UNKNOWN.';

                if (isset($response['error']) && is_string($response['error'])) {
                    $error = $response['error'];
                }

                throw new Exception($error);
            }

            return $response['result'];
        } catch (PhpException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
