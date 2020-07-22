<?php

namespace IncapsulaAPI\Adapter;

use IncapsulaAPI\Adapter\AdapterInterface;
use IncapsulaAPI\Auth\AuthInterface;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Guzzle implements AdapterInterface
{
    private $client;
    private $body = [];

    private $debug_info;
    private $res;
    private $res_message;

    /**
     * @inheritDoc
     */
    public function __construct(AuthInterface $auth, string $baseURI = null)
    {
        if ($baseURI === null) {
            $baseURI = 'https://my.incapsula.com/';
        }

        $this->setOptions([$auth->toArray()]);
        $this->setClient(new Client([
            'base_uri' => $baseURI,
            'Accept' => 'application/json',
            'timeout'  => 60.0
        ]));
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function request(string $uri, ...$options): \stdClass
    {
        $this->setOptions($options);

        $response = $this->client->request('POST', $uri, [
            'form_params' => $this->body,
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
        $object = json_decode($response->getBody());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JSONException();
        }

        foreach (['res', 'res_message', 'debug_info'] as $param) {
            if (isset($object->{$param})) {
                $this->{$param} = $object->{$param};
                unset($object->{$param});
            }
        }

        $this->checkError();
        
        return $object;
    }

    private function checkError()
    {
        if (isset($this->res) && $this->res) {
            $message = "An unknown error has occurred.";

            if (isset($this->res_message)) {
                $message = "{$this->res_message},";

                if (!empty($this->debug_info)) {
                    $message = "{$this->res_message}: {" . json_encode($this->debug_info) . "},";
                }
            }

            throw new IncapsulaException($message, $this->res);
        }
    }

    public function getDebugInfo()
    {
        return $this->debug_info;
    }
}
