<?php namespace tkj\Economics;

use SoapClient;

class Client {

    /**
     * E-conomics agreement number
     * @var integer
     */
    protected $agreement;

    /**
     * E-conomics user id
     * @var integer
     */
    protected $userId;

    /**
     * E-conomics password
     * @var string
     */
    protected $password;

    /**
     * SOAP Connection
     * @var \SoapClient
     */
    protected $client;

    /**
     * E-conomics API url
     * @var string
     */
    protected $apiUrl = 'https://www.e-conomic.com/secure/api1/EconomicWebservice.asmx?WSDL';

    /**
     * Array with debug options
     * @var array
     */
    protected $debug = array("trace"=>1, "exceptions"=>1);

    /**
     * Client constructor
     * @param integer   $agreement
     * @param integer   $userId
     * @param string    $password
     */
    public function __construct($agreement, $userId, $password)
    {
        $this->agreement = $agreement;
        $this->userId    = $userId;
        $this->password  = $password;

        $this->client = new SoapClient($this->apiUrl, $this->debug);

        $this->client->Connect(
            array(
                'agreementNumber' => $this->agreement,
                'userName'        => $this->userId,
                'password'        => $this->password
            )
        );
    }

    /**
     * Return client
     * @return \SoapClient
     */
    public function getClient()
    {
        return $this->client;
    }

}
