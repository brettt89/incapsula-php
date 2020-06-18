<?php

namespace Incapsula\API;

use Incapsula\API\Adapter;
use Incapsula\API\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;

class Guzzle implements Adapter
{
    private $client;
    private $body;

    private $debug_info;
    private $res;
    private $res_message;

    /**
     * @inheritDoc
     */
    public function __construct(Auth $auth, string $baseURI = null)
    {
        if ($baseURI === null) {
            $baseURI = 'https://my.incapsula.com/';
        }

        $this->body = $auth->toArray();
        $this->client = new Client([
            'base_uri' => $baseURI,
            'Accept' => 'application/json',
            'timeout'  => 60.0
        ]);
    }

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
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

        if (isset($object->res)) {
            $this->res = $object->res;
            unset($object->res);
        }
        if (isset($object->debug_info)) {
            $this->debugInfo = $object->debug_info;
            unset($object->debug_info);
        }
        if (isset($object->res_message)) {
            $this->res_message = $object->res_message;
            unset($object->res_message);
        }
        
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
                    $message = "{$json->res_message}: {" . json_encode($this->debug_info) . "},";
                }
            }

            throw new IncapsulaException($message, $json->res);
        }
    }

    public function getDebugInfo()
    {
        return $this->debug_info;
    }
}