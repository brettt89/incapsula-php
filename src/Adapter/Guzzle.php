<?php

namespace Incapsula\API\Adapter;

use Incapsula\API\Parameters\Auth;
use Incapsula\API\Parameters\Parameter;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Guzzle implements Adapter
{
    private $client;
    private $body;

    /**
     * @inheritDoc
     */
    public function __construct(Auth $auth, string $baseURI = null)
    {
        if ($baseURI === null) {
            $baseURI = 'https://my.incapsula.com/';
        }

        $this->body = $auth->getRequestParameters();
        $this->client = new Client([
            'base_uri' => $baseURI,
            'Accept' => 'application/json',
            'timeout'  => 30.0
        ]);
    }

    public function request(string $uri, ...$options): ResponseInterface
    {
        if (isset($options)) {
            $this->setOptions($options);
        }
        
        $response = $this->client->request('POST', $uri, [
            'form_params' => $this->body,
        ]);

        $this->checkError($response);

        unset($response->res, $response->res_message);

        return $response;
    }

    private function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            if ($value instanceof Parameter) {
                $this->body = array_merge($this->body, $value->getRequestParameters());
                continue;
            }

            if ($value instanceof Configuration) {
                $this->body = array_merge($this->body, $value->toArray());
                continue;
            }
            $this->body = array_merge($this->body, $value);
        }
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
                $message = "{$json->res_message}";

                if (isset($json->debug_info->problem)) {
                    $message = "{$json->res_message}: {$json->debug_info->problem}";

                    if (isset($json->debug_info->{'id-info'})) {
                        $infoID = $json->debug_info->{'id-info'};
                        $message = "{$json->res_message}: [$infoID]{$json->debug_info->problem}";
                    }
                }
            }

            throw new IncapsulaException($message, $json->res);
        }
    }
}
