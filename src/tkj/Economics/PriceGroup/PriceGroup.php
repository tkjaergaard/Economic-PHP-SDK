<?php namespace tkj\Economics\PriceGroup;

use tkj\Economics\Client;

class PriceGroup {

    /**
     * Client Connection
     * @var Client
     */
    protected $client;

    /**
     * Construct class and set dependencies
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
    }

    /**
     * Return all price groups
     * @return array
     */
    public function all()
    {
        return $this->client
                    ->PriceGroup_GetAll()
					->PriceGroup_GetAllResult
					->PriceGroupHandle;
    }

    /**
     * Returns the price for the product in the PriceGroup
     * If no special price is found then the sales price is returned instead
     * @param  integer $groupNo
     * @param  string   $productNo
     * @return float
     */
    public function getPrice($groupNo, $productNo)
    {
        return $this->client
                    ->PriceGroup_GetPrice(array('priceGroupHandle'=>array('Number'=>$groupNo), 'productHandle'=>array('Number'=>$productNo)))
					->PriceGroup_GetPriceResult;
    }
}
