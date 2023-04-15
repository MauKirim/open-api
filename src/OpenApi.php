<?php

namespace MauKirim\OpenApi;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class OpenApi
 * @package MauKirim\OpenApi
 */
class OpenApi
{
    public Client $client;
    public string $connection_name = 'open-api';

    /**
     * OpenApi constructor.
     * @param int $timeout
     */
    public function __construct(int $timeout)
    {
        $this->initClient($timeout);
        return $this;
    }

    /**
     * Initiate Guzzle Client
     * @param int $timeout
     * @return void
     */
    private function initClient(int $timeout)
    {
        $messageFormat = "{host} - {req_header_authorization} {request} {req_body} {error}";
        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                new Logger($this->connection_name, [new StreamHandler(storage_path('logs/' . $this->connection_name . '.log'))]),
                new MessageFormatter($messageFormat)
            )
        );
        $baseUrl = config('open-api.dev_url');
        $token = config('open-api.token');
        if (config('open-api.prod')) {
            $baseUrl = config('open-api.prod_url');
        }
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => $timeout,
            'handler' => $stack,
            'headers' => [
                "Authorization" => "Bearer " . $token,
            ]
        ]);
    }

    /**
     * Initialize OpenApi Static
     * @param int $timeout
     * @return OpenApi
     */
    public static function init(int $timeout = 10): OpenApi
    {
        return new self($timeout);
    }

    public function ping(): string
    {
        return "as";
    }


}
