<?php

namespace tkj\Economics\Unit;

use tkj\Economics\ClientInterface as Client;

class Unit
{
    /**
     * Client Connection
     * @var Client
     */
    protected $client;

    /**
     * Instance of Client
     * @var Client
     */
    protected $client_raw;

    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;
    }

    /**
     * Get handle from number
     *
     * @param integer $no
     * @return object|integer
     */
    public function getHandle($no)
    {
        if (is_object($no) and isset($no->Id)) {
            return $no;
        }

        return $this->client
                    ->Unit_FindByNumber([
                        'number' => $no,
                    ])
                    ->Unit_FindByNumberResult;
    }
}
