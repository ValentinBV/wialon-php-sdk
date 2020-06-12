<?php

namespace valentinbv\Wialon\Request\Extra;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use valentinbv\Wialon\Exception\WialonRequestException;
use valentinbv\Wialon\Exception\ReadOnlyException;
use valentinbv\Wialon\Exception\InexistentPropertyException;
use valentinbv\Wialon\Request\BaseRequest;

class Events extends BaseRequest
{
    /**
     * @var string
     */
    protected $host = 'hst-api.wialon.com';

    /**
     * @var string
     */
    protected $path = '/avl_evts';

    /**
     * Execut request to Wialon API
     * @return array
     * @throws \valentinbv\Wialon\Exception\WialonRequestException
     */
    public function execute(): array
    {
        return $this->request(
            [
                'sid' => $this->sid
            ]
        );
    }
}