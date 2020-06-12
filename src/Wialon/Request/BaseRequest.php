<?php

namespace valentinbv\Wialon\Request;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use valentinbv\Wialon\Exception\WialonRequestException;
use valentinbv\Wialon\Exception\ReadOnlyException;
use valentinbv\Wialon\Exception\InexistentPropertyException;

class BaseRequest 
{
    /**
     * @var string
     */
    protected $sid = '';

    /**
     * @var string
     */
    protected $protocol = 'https';

    /**
     * @var string
     */
    protected $host = 'hst-api.wialon.com';

    /**
     * @var int
     */
    protected $port = 443;

    /**
     * @var string
     */
    protected $path = '/wialon/ajax.html';

    /**
     * @var \GuzzleHttp\ClientInterface $httpClient
     */
    protected $httpClient;

    /**
     * BaseRequest constructor
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param string $host
     * @param string $protocol
     * @param int $port
     */
    public function __construct(
        ClientInterface $httpClient, 
        string $host = '', 
        string $protocol = '', 
        int $port = 0)
    {
        if ($host) {
            $this->host = $host;
        }
        if ($protocol) {
            $this->protocol = $protocol;
        }
        if ($port) {
            $this->port = $port;
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
     * Set sid
     * @param string $sid
     * @return $this
     */
    protected function setSid(string $sid)
    {
        $this->sid = $sid;
    }

    /**
     * Get sid
     * @return string
     */
    protected function getSid(): string
    {
        return $this->sid;
    }

    /**
     * Get host
     * @return string
     */
    protected function getHost(): string
    {
        return $this->host;
    }

    /**
     * Set path
     * @param string $path
     * @return $this
     */
    protected function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * Get path
     * @return string
     */
    protected function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get port
     * @return string
     */
    protected function getPort(): string
    {
        return $this->port;
    }

    /**
     * Get url
     * @return string
     */
    public function prepareUrl(): string
    {
        return 
            $this->protocol . '://' 
            . $this->host
            . ($this->port ? ':' . $this->port : '') 
            . $this->path;
    }

     /**
     * Request to Wialon API by params
     * @param array $params
     * @return array
     * @throws \valentinbv\Wialon\Exception\WialonRequestException
     */
    public function request(array $params = []): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->prepareUrl(), [
                'form_params' => $params
            ]);
        } catch (TransferException $e) {
            throw new WialonRequestException($e);
        }
        $result = $this->decodeBody($response->getBody()->getContents());

        return $result;
    }
}