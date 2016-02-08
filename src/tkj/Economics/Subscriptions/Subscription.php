<?php namespace tkj\Economics\Subscriptions;

use tkj\Economics\ClientInterface as Client;
use tkj\Economics\Subscriptions\Subscriber;
use Exception;

class Subscription {

    protected $client;

    protected $client_raw;

    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;
    }

    public function getHandle($no)
    {
        if( is_object($no) AND isset($no->Id) ) return $no;

        if( @$result = $this->client
                ->Subscription_FindByNumber(array('number'=>$no))
                ->Subscription_FindByNumberResult
        ) return $result;
    }

    public function create(array $data)
    {
        //     https://api.e-conomic.com/secure/api1/EconomicWebService.asmx?op=Subscription_Create
        //
        //     subscriptionNumber               Subscription number for the subscription
        //     subscriptionName                 Subscription name for the subscription
        //     description                      Description for the subscription
        //     includeName                      Enables include name for the subscription (boolean)
        //     subscriptionInterval             Subscription interval for the subscription
        //     calendarYearBasis                Enables calendar year for the subscription (boolean)
        //     collection                       Collection for the subscription
        //     addPeriod                        Enables add period for the subscription (boolean)
        //     allowMoreThanOneForEachDebtor    Enables to allow more than one for each debitor for the subscription

        $subscriptionHandle = $this->client
            ->Subscription_Create($data)
            ->Subscription_CreateResult;

        return $subscriptionHandle;
    }

    public function delete($no)
    {
        $handle = $this->getHandle($no);

        try {
            $this->client
                ->Subscription_Delete(array('subscriptionHandle' => $handle));
        }
        catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function all()
    {
        $handles = $this->client
            ->Subscription_GetAll()
            ->Subscription_GetAllResult;

        return $this->getArrayFromHandles($handles);
    }

    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->Subscription_GetDataArray(array('entityHandles'=>$handles))
            ->Subscription_GetDataArrayResult
            ->SubscriptionData;
    }

    public function nextAvailableNumber()
    {
        return $this->client
            ->Subscription_GetNextAvailableNumber()
            ->Subscription_GetNextAvailableNumberResult;
    }

    public function subscribers($no)
    {
        $handle = $this->getHandle($no);

        $subscriberHandles = $this->client
            ->Subscription_GetSubscribers(array('subscriptionHandle' => $handle))
            ->Subscription_GetSubscribersResult;

        $subscriber = new Subscriber($this->client_raw, $handle);

        return $subscriber->subscribers($subscriberHandles);
    }

    public function addSubscriber($no, array $data)
    {
        $handle = $this->getHandle($no);

        $subscriber = new Subscriber($this->client_raw, $handle);

        return $subscriber->create($data);
    }

    public function lines($no)
    {
        $handle = $this->getHandle($no);

        $lineHandles = $this->client
            ->Subscription_GetSubscriptionLines(array('subscriptionHandle' => $handle))
            ->Subscription_GetSubscriptionLinesResult;

        // @TODO: New subsciprtion line instance
    }

    public function update($no, array $data)
    {
        $handle = $this->getHandle($no);

        $data['Handle'] = $handle;
        $data['Id'] = $handle->Id;

        return $this->client
            ->Subscription_UpdateFromData($data)
            ->Subscription_UpdateFromDataResult;
    }

    public function get($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Subscription_GetData(array('entityHandle' => $handle))
            ->Subscription_GetDataResult;
    }
}