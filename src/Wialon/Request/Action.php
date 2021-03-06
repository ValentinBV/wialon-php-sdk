<?php

namespace valentinbv\Wialon\Request;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use valentinbv\Wialon\Exception\WialonRequestException;
use valentinbv\Wialon\Exception\ReadOnlyException;
use valentinbv\Wialon\Exception\InexistentPropertyException;

class Action extends BaseRequest
{
    /**
     * Execute request to Wialon API by action and params
     * @param string $svc
     * @param array $params
     * @return array
     * @throws \valentinbv\Wialon\Exception\WialonRequestException
     */
    public function execute(string $svc, array $params = []): array
    {
        return $this->request(
            [
                'sid' => $this->sid,
                'svc'=> $svc,
                'params' => json_encode($params)
            ]
        );
    }
}