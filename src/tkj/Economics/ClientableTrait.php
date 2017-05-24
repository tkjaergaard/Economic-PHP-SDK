<?php namespace tkj\Economics;

trait ClientableTrait
{
    /**
     * SOAP Connection
     * @var \SoapClient
     */
    protected $client;

    /**
     * E-conomics API url
     * @var string
     */
    protected $apiUrl = 'https://api.e-conomic.com/secure/api1/EconomicWebService.asmx?wsdl';

    /**
     * Array with debug options
     * @var array
     */
    protected $debug = ["trace" => 1, "exceptions" => 1];

    /**
     * Return client
     * @return \SoapClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
