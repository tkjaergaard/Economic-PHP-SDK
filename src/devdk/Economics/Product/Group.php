<?php namespace devdk\Economics\Product;

use devdk\Economics\Client;

class Group {

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
     * Get Product Group handle
     * by group number
     * @param  integer $no
     * @return object
     */
    public function getHandle($no)
    {
        if( is_object($no) AND isset($no->Number) ) return $no;

        return $this->client
                    ->ProductGroup_FindByNumber(array('number'=>$no))
                    ->ProductGroup_FindByNumberResult;
    }
    /**
     * Get Products Groups from handles
     * @param  object $handels
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->Product_GetDataArray(array('entityHandles'=>array('ProductGroupHandle'=>$handles)))
            ->Product_GetDataArrayResult
            ->ProductData;
    }

    /**
     * Get all Product Groups
     * @return array
     */
    public function all()
    {
        $handles = $this->client
            ->ProductGroup_GetAll()
            ->ProjectGroup_GetAllResult
            ->ProjectGroupHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Get specific Product Group
     * @param  integer $no
     * @return array
     */
    public function get($no)
    {
        $handle = $this->getHandle($no);

        return $this->getArrayFromHandles($handle);
    }

    /**
     * Get all Products of a
     * specific Product Group
     * @param  integer $no
     * @return array
     */
    public function products($no)
    {
        $handle = $this->getHandle($no);

        $productHandles = $this->client
            ->ProductGroup_GetProducts(array('productGroupHandle'=>$handle))
            ->ProductGroup_GetProductsResult
            ->ProductHandle;

        $product = new Product($this->client_raw);

        return $product->getArrayFromHandles($productHandles);
    }

    public function create()
    {

    }

    public function update($no, array $data)
    {

    }

}