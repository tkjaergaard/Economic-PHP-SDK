<?php

namespace tkj\Economics\Subscriptions;

use tkj\Economics\Client;
use tkj\Economics\Debtor\Debtor;
use InvalidArgumentException;

class Subscriber
{
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
     * @var object
     */
    protected $subscriptionHandle;

    public function __construct(Client $client, $subscriptionHandle)
    {
        $this->client       = $client->getClient();
        $this->client_raw   = $client;

        $this->subscriptionHandle = $subscriptionHandle;
    }

    /**
     * Create subscriber
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $data['subscriptionHandle'] = $this->subscriptionHandle;

        if (!isset($data['debtor'])) {
            throw new InvalidArgumentException("It's required to provide a debtor number.");
        }

        $debtor = new Debtor($this->client_raw);

        $data['debtorHandle'] = $debtor->getHandle($data['debtor']);

        unset($data['debtor']);

        $data = array_merge([
            'startDate'          => date('Y-m-d H:i:s'),
            'registeredDate'     => date('Y-m-d H:i:s'),
            'endDate'            => date('Y-m-d H:i:s', strtotime('+99 YEAR')),
        ], $data);

        return $this->client
            ->Subscriber_Create($data)
            ->Subscriber_CreateResponse
            ->Subscriber_CreateResult;
    }

    /**
     * Subscribers
     *
     * @param $handles
     * @return mixed
     */
    public function subscribers($handles)
    {
        $subscriber = $this->client
            ->Subscriber_GetDataArray([
                'entityHandles' => $handles,
            ])
            ->Subscriber_GetDataArrayResult;

        $debtor = new Debtor($this->client_raw);
        $subscriber->debtor = $debtor->get($subscriber->SubscriberData->DebtorHandle);

        return $subscriber;
    }
}
