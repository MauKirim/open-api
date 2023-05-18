<?php

namespace MauKirim\OpenApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @throws \Exception
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
     * @throws \Exception
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
        if ($token === '') {
            throw new \Exception("Token is not set");
        }
        if (config('open-api.api_env') === "prod") {
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
     * @throws \Exception
     */
    public static function init(int $timeout = 10): OpenApi
    {
        return new self($timeout);
    }

    public function ping(): string
    {
        return "PONG";
    }

    /**
     * Send text to msdn
     * @param string $msdn
     * @param string $message
     * @param array $list_buttons button only work for whatsapp personal not business !!!
     * @return mixed
     * @throws GuzzleException
     */
    public function send(string $msdn, string $message, array $list_buttons = [])
    {
        $response = $this->client->request('POST', 'api/v1/messages/send', [
            'json' => [
                'msdn' => $msdn,
                'message' => $message,
                'list_buttons' => $list_buttons
            ]
        ]);
        try {
            $data = $this->responseArray($response->getBody()->getContents());
            return $data["process_id"];
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Parse response from http
     * @param string $response
     * @return mixed
     * @throws \Exception
     */
    private function responseArray(string $response)
    {
        $response = json_decode($response, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \Exception("Response is not json");
        }
        return $response;
    }

    /**
     * Send image to msdn
     * @param string $msdn
     * @param string $message
     * @param UploadedFile $file
     * @return mixed
     * @throws GuzzleException
     */
    public function sendImage(string $msdn, string $message, UploadedFile $file)
    {
        $response = $this->client->request('POST', 'api/v1/messages/send/image', [
            'multipart' => [
                [
                    'name' => 'msdn',
                    'contents' => $msdn
                ],
                [
                    'name' => 'message',
                    'contents' => $message
                ],
                [
                    'name' => 'file',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ]
            ]
        ]);
        try {
            $data = $this->responseArray($response->getBody()->getContents());
            return $data["process_id"];
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Send document to msdn
     * @param string $msdn
     * @param string $message
     * @param UploadedFile $file
     * @return false|mixed
     * @throws GuzzleException
     */
    public function sendDocument(string $msdn, string $message, UploadedFile $file)
    {
        $response = $this->client->request('POST', 'api/v1/messages/send/document', [
            'multipart' => [
                [
                    'name' => 'msdn',
                    'contents' => $msdn
                ],
                [
                    'name' => 'message',
                    'contents' => $message
                ],
                [
                    'name' => 'file',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ]
            ]
        ]);
        try {
            $data = $this->responseArray($response->getBody()->getContents());
            return $data["process_id"];
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Send OTP to msdn
     * @param string $msdn
     * @return mixed
     * @throws GuzzleException
     */

    public function sendOTP(string $msdn)
    {
        $response = $this->client->request('POST', 'api/v1/otp/send', [
            'json' => [
                'msdn' => $msdn
            ]
        ]);
        try {
            $data = $this->responseArray($response->getBody()->getContents());
            return $data["process_id"];
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Validate OTP
     * @param string $msdn
     * @param string $otp
     * @return mixed
     * @throws GuzzleException
     */

    public function validateOTP(string $msdn, string $otp)
    {
        $response = $this->client->request('POST', 'api/v1/otp/validate', [
            'json' => [
                'msdn' => $msdn,
                'otp' => $otp
            ]
        ]);
        try {
            $data = $this->responseArray($response->getBody()->getContents());
            return $data["process_id"];
        } catch (\Exception $e) {
            return false;
        }
    }


}
