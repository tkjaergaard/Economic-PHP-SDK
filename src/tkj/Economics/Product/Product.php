<?php namespace tkj\Economics\Product;

use tkj\Economics\ClientInterface as Client;
use tkj\Economics\Unit\Unit;

class Product {

    /**
     * Client Connection
     * @var devdk\Economics\Client
     */
    protected $client;

    /**
     * Instance of Client
     * @var devdk\Economics\Client
     */
    protected $client_raw;

    /**
     * Construct class and set dependencies
     * @param devdk\Economics\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;
    }

    /**
     * Get Product handle by product number
     * @param  integer $no
     * @return object
     */
    public function getHandle($no)
    {
        if( is_object($no) AND isset($no->Number) ) return $no;

        return $this->client
                    ->Product_FindByNumber(array('number'=>$no))
                    ->Product_FindByNumberResult;
    }

    /**
     * Get Products from handles
     * @param  object $handels
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->Product_GetDataArray(array('entityHandles'=>array('ProductHandle'=>$handles)))
            ->Product_GetDataArrayResult
            ->ProductData;
    }

    public function get($no)
    {
        $handle = $this->getHandle($no);

        return $this->getArrayFromHandles($handle);
    }

    public function all()
    {
        $handles = $this->client
            ->Product_GetAll()
            ->Product_GetAllResult
            ->ProductHandle;

        return $this->getArrayFromHandles($handles);
    }

    public function accessible()
    {
        $handles = $this->client
            ->Product_GetAllAccessible()
            ->Product_GetAllAccessibleResult
            ->ProductHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Get product Stock
     * @param  [type] $no [description]
     * @return [type]     [description]
     */
    public function stock($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Product_GetAvailable(array('productHandle'=>$handle))
            ->Product_GetAvailableResult;
    }

    /**
     * Create a new Product
     * @param  array  $data
     * @return object
     */
    public function create(array $data)
    {
        if(isset($data["number"])) {
            $number = $data["number"];
        }else{
            $all    = $this->all();
            $number = end($all)->Number + 1;
        }

        $group       = new Group($this->client_raw);
        $groupHandle = $group->getHandle($data['group']);

        $productHandle = $this->client
            ->Product_Create(array(
                "number"             => $number,
                "productGroupHandle" => $groupHandle,
                "name"               => $data["name"]
            ))
            ->Product_CreateResult;

        unset( $data['name'] );
        unset( $data['group'] );

        $this->client
            ->Product_SetIsAccessible(array('productHandle' => $productHandle, "value" => true));

        return $this->update($productHandle, $data);
    }

    /**
     * Update an existion Product
     * @param  integer $no
     * @param  array   $data
     * @return object
     */
    public function update($no, array $data)
    {
        $handle  = $this->getHandle($no);

        foreach( $data as $field => $value )
        {
            $request = array('productHandle' => $handle, "value" => $value);

            switch( strtolower($field) )
            {
                case 'cost';
                    $this->client
                        ->Product_SetCostPrice($request);
                    break;
                case 'description':
                    $this->client
                        ->Product_SetDescription($request);
                    break;
                case 'name':
                    $this->client
                        ->Product_SetName($request);
                    break;
                case 'group':
                    unset($request["value"]);

                    $group                  = new Group($this->client_raw);
                    $groupHandle            = $group->getHandle($value);
                    $request["valueHandle"] = $groupHandle;

                    $this->client
                        ->Product_SetProductGroup($request);
                    break;
                case 'price':
                    $this->client
                        ->Product_SetSalesPrice($request);
                    break;
                case 'rrp':
                    $this->client
                        ->Product_SetRecommendedPrice($request);
                    break;
                case 'unit':
                    unset($request["value"]);

                    $unit                   = new Unit($this->client_raw);
                    $unitHandle             = $unit->getHandle($value);
                    $request["valueHandle"] = $unitHandle;

                    $this->client
                        ->Product_SetUnit($request);
                    break;
            }
        }

        return $this->getArrayFromHandles( $handle );
    }

    /**
     * Find a product by it's name.
     * The name has to be an exact match in
     * order to get any returns. Unfortunately
     * this method can't be used very well as a
     * search function.
     *
     * @param  string $query
     * @return null|stdClass
     */
    public function find($query)
    {
        $handles = $this->client
            ->Product_FindByName(array('name'=>$query))
            ->Product_FindByNameResult;

        if( ! isset($handles->ProductHandle) )
            return null;

        return $this->getArrayFromHandles($handles->ProductHandle);
    }

    /**
     * Get the price of a product by currency code
     * @param  string $number
     * @param  string $code
     * @return string
     */
    public function getPriceByCurrency($number, $code)
    {
		return $this->client->ProductPrice_GetPrice(array('productPriceHandle' => array('Id1' => $number, 'Id2' => $code)))->ProductPrice_GetPriceResult;
    }


}
