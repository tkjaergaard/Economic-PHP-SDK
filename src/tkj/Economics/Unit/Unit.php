<?php namespace tkj\Economics\Unit;

use tkj\Economics\Client;

class Unit {

    /**
     * Client Connection
     * @var devdk\Economics\Client
     */
    protected $client;

    /**
     * Instance of Client
     * @var devdk\Economics\Client
     */
    protected $client_raw;

    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;
    }

    public function getHandle($no)
    {
        if( is_object($no) AND isset($no->Id) ) return $no;

        return $this->client
                    ->Unit_FindByNumber(array('number'=>$no))
                    ->Unit_FindByNumberResult;
    }

}