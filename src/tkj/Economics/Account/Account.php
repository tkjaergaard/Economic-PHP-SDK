<?php

namespace tkj\Economics\Account;

use tkj\Economics\Client;

class Account {

    /**
     * Client Connection
     *
     * @var Client
     */
    protected $client;

    /**
     * Instance of Client
     *
     * @var Client
     */
    protected $client_raw;

    /**
     * Construct class and set dependencies
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;
    }

    /**
     * Get Debtor Group handle by number
     *
     * @param  integer $no
     * @return object
     */
    public function getHandle($no)
    {
        if (is_object($no) AND isset($no->Number)) {
            return $no;
        }

        return $this->client
            ->Account_FindByNumber([
                'number'=>$no,
            ])
            ->Account_FindByNumberResult;
    }

    /**
     * Get Debtor Groups from handles
     *
     * @param array $handles
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->Account_GetDataArray([
                'entityHandles' => $handles,
            ])
            ->Account_GetDataArrayResult
            ->AccountData;
    }
}