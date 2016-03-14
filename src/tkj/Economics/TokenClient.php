<?php

namespace tkj\Economics;

use SoapClient;

class TokenClient implements ClientInterface
{
    use ClientableTrait;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $appToken;

    /**
     * @var string
     */
    protected $appIdentifier;

    /**
     * @param string $token
     * @param string $appToken
     * @param string $appIdentifier
     */
    public function __construct($token, $appToken, $appIdentifier)
    {
        $this->token = $token;
        $this->appToken = $appToken;
        $this->appIdentifier = $appIdentifier;

        $this->setupAppIdentifierContext();

        $this->client = $this->setupClient();
    }

    /**
     * Setup app identifier context
     */
    protected function setupAppIdentifierContext()
    {
        $this->debug['stream_context'] = stream_context_create([
           'http' => ['header' => 'X-EconomicAppIdentifier: ' . $this->appIdentifier]
        ]);
    }

    /**
     * Setup client
     *
     * @return SoapClient
     */
    protected function setupClient()
    {
        $client = new SoapClient($this->apiUrl, $this->debug);
        $client->ConnectWithToken([
            'token' => $this->token,
            'appToken' => $this->appToken,
        ]);

        return $client;
    }
}
