<?php namespace tkj\Economics\Subscriptions;

use tkj\Economics\Client;
use tkj\Economics\Debtor\Debtor;
use InvalidArgumentException;

class Subscriber {

    protected $client;

    protected $client_raw;

    protected $subscriptionHandle;

    public function __construct(Client $client, $subscriptionHandle)
    {
        $this->client       = $client->getClient();
        $this->client_raw   = $client;

        $this->subscriptionHandle = $subscriptionHandle;
    }

    public function create(array $data)
    {
        $data['subscriptionHandle'] = $this->subscriptionHandle;

        if( !isset($data['debtor']))
            throw new InvalidArgumentException("It's required to provide a debtor number.");

        $debtor = new Debtor($this->client_raw);

        $data['debtorHandle'] = $debtor->getHandle($data['debtor']);

        unset($data['debtor']);

        $data = array_merge(array(
            'startDate'          => date('Y-m-d H:i:s'),
            'registeredDate'     => date('Y-m-d H:i:s'),
            'endDate'            => date('Y-m-d H:i:s', strtotime('+99 YEAR'))
        ), $data);

        return $this->client
            ->Subscriber_Create($data)
            ->Subscriber_CreateResponse
            ->Subscriber_CreateResult;
    }

    public function subscribers($handles)
    {
        $subscriber = $this->client
            ->Subscriber_GetDataArray(array('entityHandles' => $handles))
            ->Subscriber_GetDataArrayResult;

        $subscriber->debtor = (new Debtor($this->client_raw))->get($subscriber->SubscriberData->DebtorHandle);

        return $subscriber;
    }

}