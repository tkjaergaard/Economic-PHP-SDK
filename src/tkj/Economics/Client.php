<?php

namespace tkj\Economics;

use SoapClient;

class Client implements ClientInterface
{
    use ClientableTrait;
    /**
     * E-conomic agreement number
     *
     * @var integer
     */
    protected $agreement;

    /**
     * E-conomic user id
     *
     * @var integer
     */
    protected $userId;

    /**
     * E-conomic password
     *
     * @var string
     */
    protected $password;

    /**
     * SOAP Connection
     *
     * @var SoapClient
     */
    protected $client;

    /**
     * E-conomic API url
     *
     * @var string
     */
    protected $apiUrl = 'https://www.e-conomic.com/secure/api1/EconomicWebservice.asmx?WSDL';

    /**
     * Array with debug options
     *
     * @var array
     */
    protected $debug = [
        "trace" => 1,
        "exceptions" => 1
    ];

    /**
     * Client constructor
     * @param integer   $agreement
     * @param integer   $userId
     * @param string    $password
     */
    /**
     * Client structor
     *
     * @param integer $agreement
     * @param integer $userId
     * @param string $password
     */
    public function __construct($agreement, $userId, $password)
    {
        $this->agreement = $agreement;
        $this->userId    = $userId;
        $this->password  = $password;

        $this->client = new SoapClient($this->apiUrl, $this->debug);

        $this->client->Connect([
            'agreementNumber' => $this->agreement,
            'userName'        => $this->userId,
            'password'        => $this->password
        ]);
    }

    /**
     * Return client
     *
     * @return SoapClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
