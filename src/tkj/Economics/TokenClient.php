<?php namespace tkj\Economics;

use SoapClient;

class TokenClient implements ClientInterface
{

    use ClientableTrait;

    protected $token;

    protected $appToken;

    protected $appIdentifier;

    public function __construct($token, $appToken, $appIdentifier, array $options = [])
    {
        $this->token = $token;
        $this->appToken = $appToken;
        $this->appIdentifier = $appIdentifier;

        $this->debug = array_merge($this->debug, $options);

        $this->setupAppIndentifierContex();

        $this->client = $this->setupClient();
    }

    protected function setupAppIndentifierContex()
    {
        $this->debug['stream_context'] = stream_context_create([
            'http' => ['header' => 'X-EconomicAppIdentifier: ' . $this->appIdentifier]
        ]);
    }

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
