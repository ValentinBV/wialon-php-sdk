<?php

namespace valentinbv\Wialon\Extra;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use valentinbv\Wialon\Exception\WialonRequestException;
use valentinbv\Wialon\Exception\ReadOnlyException;
use valentinbv\Wialon\Exception\InexistentPropertyException;

class Events {

    /**
     * @var string
     */
    private $sid = '';
    /**
     * @var string
     */
    private $host = 'https://hst-api.wialon.com/avl_evts';
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $httpClient;

    /**
     * Action constructor
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param string $host
     */
    public function __construct(ClientInterface $httpClient, string $host = '') {
        if ($host) {
            $this->host = $host;
        }
        $this->httpClient = $httpClient;
    }

    public function __set($var, $value)
    {
        $function = 'set' . ucfirst($var);
        if (method_exists($this, $function)) {
            $this->$function($value);
        } else {
            if (method_exists($this, 'get' . ucfirst($var))) {
                throw new ReadOnlyException("property $var is read-only");
            } else {
                throw new InexistentPropertyException("Inexistent property: $var");
            }
        }
    }

    public function __get($var)
    {
        $function = 'get' . ucfirst($var);
        if (method_exists($this, $function)) {
            return $this->$function();
        } else {
            throw new InexistentPropertyException("Inexistent property: $var");
        }
    }
    /**
     * Set sid
     * @param string $sid
     * @return $this
     */
    private function setSid(string $sid)
    {
        $this->sid = $sid;
        
        return $this;
    }

    /**
     * Get sid
     * @return string
     */
    private function getSid(): string
    {
        return $this->sid;
    }

    /**
     * Get host
     * @return string
     */
    private function getHost(): string
    {
        return $this->host;
    }

   /**
     * Decode query result body.
     * @param string $body
     * @return array
     */
    public function decodeBody(string $body): array
    {
        $decodeBody = json_decode($body, true);

        if ($decodeBody === null || !is_array($decodeBody)) {
            $decodeBody = [];
        }

        return $decodeBody;
    }

    /**
     * Request to Wialon API
     * @return array
     * @throws \valentinbv\Wialon\Exception\WialonRequestException
     */
    public function getList(): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->host, [
                'form_params' => [
                    'sid' => $this->sid,
                ]
            ]);
        } catch (TransferException $e) {
            throw new WialonRequestException($e);
        }
        $result = $this->decodeBody($response->getBody()->getContents());

        return $result;
    }
}