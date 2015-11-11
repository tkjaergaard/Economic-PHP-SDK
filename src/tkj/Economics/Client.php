<?php namespace tkj\Economics;

use SoapClient;

class Client {

    /**
     * E-conomics token (Access ID)
     * @var string
     */
    protected $token;

    /**
     * E-conomics appToken (app ID)
     * @var string
     */
    protected $appToken;

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
     * @param string $token
     * @param string $appToken
     */
    public function __construct($token, $appToken)
    {
        $this->token    = $token;
        $this->appToken = $appToken;

        // Add X-EconomicAppIdentifier to all SOAP calls
        $debug['stream_context'] = stream_context_create(array(
           'http' => array(
               'header' => 'X-EconomicAppIdentifier: ' . $appToken
           )
        ));
        $this->client = new SoapClient($this->apiUrl, $this->debug);

        $this->client->ConnectWithToken(
            array(
                'token'    => $this->token,
                'appToken' => $this->appToken,
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