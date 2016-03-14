<?php

namespace tkj\Economics\Subscriptions;

use tkj\Economics\ClientInterface as Client;
use tkj\Economics\Subscriptions\Subscriber;
use Exception;

class Subscription
{
    /**
     * Client Connection
     * @var Client
     */
    protected $client;

    /**
     * Instance of Client
     * @var Client
     */
    protected $client_raw;

    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;
    }

    /**
     * Get handle
     *
     * @param integer $no
     * @return object|null
     */
    public function getHandle($no)
    {
        if (is_object($no) and isset($no->Id)) {
            return $no;
        }

        @$result = $this->client
            ->Subscription_FindByNumber([
                'number'=>$no,
            ])
            ->Subscription_FindByNumberResult;

        if ($result) {
            return $result;
        }

        return null;
    }

    /**
     * Create
     *
     *     https://api.e-conomic.com/secure/api1/EconomicWebService.asmx?op=Subscription_Create
     *
     *     subscriptionNumber               Subscription number for the subscription
     *     subscriptionName                 Subscription name for the subscription
     *     description                      Description for the subscription
     *     includeName                      Enables include name for the subscription (boolean)
     *     subscriptionInterval             Subscription interval for the subscription
     *     calendarYearBasis                Enables calendar year for the subscription (boolean)
     *     collection                       Collection for the subscription
     *     addPeriod                        Enables add period for the subscription (boolean)
     *     allowMoreThanOneForEachDebtor    Enables to allow more than one for each debitor for the subscription
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $subscriptionHandle = $this->client
            ->Subscription_Create($data)
            ->Subscription_CreateResult;

        return $subscriptionHandle;
    }

    /**
     * Delete
     *
     * @param integer $no
     * @return bool
     */
    public function delete($no)
    {
        $handle = $this->getHandle($no);

        try {
            $this->client
                ->Subscription_Delete([
                    'subscriptionHandle' => $handle,
                ]);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        $handles = $this->client
            ->Subscription_GetAll()
            ->Subscription_GetAllResult;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * @param $handles
     * @return mixed
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->Subscription_GetDataArray([
                'entityHandles' => $handles,
            ])
            ->Subscription_GetDataArrayResult
            ->SubscriptionData;
    }

    /**
     * @return mixed
     */
    public function nextAvailableNumber()
    {
        return $this->client
            ->Subscription_GetNextAvailableNumber()
            ->Subscription_GetNextAvailableNumberResult;
    }

    /**
     * @param integer $no
     * @return mixed
     */
    public function subscribers($no)
    {
        $handle = $this->getHandle($no);

        $subscriberHandles = $this->client
            ->Subscription_GetSubscribers([
                'subscriptionHandle' => $handle,
            ])
            ->Subscription_GetSubscribersResult;

        $subscriber = new Subscriber($this->client_raw, $handle);

        return $subscriber->subscribers($subscriberHandles);
    }

    /**
     * @param integer $no
     * @param array $data
     * @return mixed
     */
    public function addSubscriber($no, array $data)
    {
        $handle = $this->getHandle($no);

        $subscriber = new Subscriber($this->client_raw, $handle);

        return $subscriber->create($data);
    }

    /**
     * (Unfinished)
     * TODO: New subscription line instance
     *
     * @param $no
     */
    public function lines($no)
    {
        $handle = $this->getHandle($no);

        $lineHandles = $this->client
            ->Subscription_GetSubscriptionLines([
                'subscriptionHandle' => $handle,
            ])
            ->Subscription_GetSubscriptionLinesResult;
    }

    /**
     * Update
     *
     * @param integer $no
     * @param array $data
     * @return mixed
     */
    public function update($no, array $data)
    {
        $handle = $this->getHandle($no);

        $data['Handle'] = $handle;
        $data['Id'] = $handle->Id;

        return $this->client
            ->Subscription_UpdateFromData($data)
            ->Subscription_UpdateFromDataResult;
    }

    /**
     * Get from number
     *
     * @param integer $no
     * @return mixed
     */
    public function get($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Subscription_GetData([
                'entityHandle' => $handle,
            ])
            ->Subscription_GetDataResult;
    }
}
