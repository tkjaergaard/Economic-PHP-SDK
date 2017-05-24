<?php namespace tkj\Economics;

use SoapClient;

class Client implements ClientInterface
{
    use ClientableTrait;

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
     * Client constructor
     * @param string $token
     * @param string $appToken
     * @param $appIdentifier (example: MyCoolIntegration/1.1 (http://example.com/MyCoolIntegration/; MyCoolIntegration@example.com) BasedOnSuperLib/1.4)
     */
    public function __construct($token, $appToken, $appIdentifier)
    {
        $this->token    = $token;
        $this->appToken = $appToken;

        // Add X-EconomicAppIdentifier to all SOAP calls
        $debug['stream_context'] = stream_context_create(array(
           'http' => array(
               'header' => 'X-EconomicAppIdentifier: ' . $appIdentifier
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
}
