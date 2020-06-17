<?php

namespace Incapsula\API\Adapter;

use Incapsula\API\Interfaces\Adapter;
use Incapsula\API\Parameters\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Guzzle implements Adapter
{
    private $client;
    private $body;
    private $debug=false;
    private $debugInfo;

    /**
     * @inheritDoc
     */
    public function __construct(Auth $auth, string $baseURI = null, bool $debug = false)
    {
        if ($baseURI === null) {
            $baseURI = 'https://my.incapsula.com/';
        }

        $this->debug = $debug;

        $this->body = $auth->getRequestParameters();
        $this->client = new Client([
            'base_uri' => $baseURI,
            'Accept' => 'application/json',
            'timeout'  => 60.0
        ]);
    }

    public function request(string $uri, ...$options): \stdClass
    {
        if (isset($options)) {
            $this->setOptions($options);
        }

        $response = $this->client->request('POST', $uri, [
            RequestOptions::JSON => $this->body,
        ]);

        return $this->parseResponse($response);
    }

    private function setOptions(array $options)
    {
        foreach ($options as $value) {
            $this->body = array_merge($this->body, $value);
        }
    }

    private function parseResponse(ResponseInterface $response): \stdClass
    {
        $this->checkError($response);
        
        $object = json_decode($response->getBody());

        // Cleanup
        if ($this->debug) $this->debugInfo = $object->debug_info;
        unset($object->res, $object->res_message);
        unset($object->debug_info);
        
        return $object;
    }

    private function checkError(ResponseInterface $response)
    {
        $json = json_decode($response->getBody());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JSONException();
        }

        if (isset($json->res) && $json->res) {
            $message = "An unknown error has occurred.";

            if (isset($json->res_message)) {
                $message = "{$json->res_message},";

                if (isset($json->debug_info)) {
                    $debug_info = json_encode($json->debug_info);
                    $message = "{$json->res_message}: {$debug_info},";
                }
            }

            throw new IncapsulaException($message, $json->res);
        }
    }

    public function getDebugInfo()
    {
        return $this->debugInfo;
    }
}
