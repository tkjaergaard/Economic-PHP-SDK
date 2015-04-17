<?php namespace tkj\Economics\Order;

use tkj\Economics\Client;
use tkj\Economics\Unit\Unit;
use tkj\Economics\Product\Product;

class Line {

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
     * Order handle for creating
     * or editing lines
     * @var object
     */
    protected $orderHandle;

    /**
     * Construct class and set dependencies
     * @param devdk\Economics\Client $client
     */
    public function __construct(Client $client, $orderHandle=NULL)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;

        if( $orderHandle )
        {
            $this->orderHandle = $orderHandle;
        }
    }

    /**
     * Set Order handle for editing or
     * creating lines
     * @param object $handle
     */
    public function setHandle($handle)
    {
        $this->orderHandle = $handle;
        return $this;
    }

    /**
     * Get Order Lines from handles
     * @param  object $handels
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
         return $this->client
            ->OrderLine_GetDataArray(array('entityHandles'=>$handles))
            ->OrderLine_GetDataArrayResult->OrderLineData;
    }

    /**
     * Add Order Line
     * @param array $data
     */
    public function add(array $data)
    {
        $defaults = array(
            "description" => null,
            "price"       => null,
            "discount"    => null,
            "qty"         => 1,
            "unit"        => null
        );

        $merged = array_merge($defaults, $data);

        $line = $this->create($this->orderHandle);

        if( isset($merged['product']) )
        {
            $this->product($line, $merged['product']);
            unset( $merged['product'] );
        }

        return $this->update($data, $line);
    }

    /**
     * Update Order Line by data
     * @param  array  $data
     * @param  object $line
     * @return object
     */
    public function update(array $data, $line)
    {
        if( is_integer($line) )
        {
            $line = array('Id' => $line);
        }

        foreach( $data as $name => $value )
        {
            if( is_null($value) )
                continue;

            switch( strtolower($name) )
            {
                case 'description':
                    $this->description($line, $value);
                    break;
                case 'price':
                    $this->price($line, $value);
                    break;
                case 'discount':
                    $this->discount($line, $value);
                    break;
                case 'qty':
                    $this->qty($line, $value);
                    break;
                case 'unit':
                    $this->unit($line, $value);
                    break;
            }
        }

        return $this->getArrayFromHandles( array('OrderLineHandle'=>$line) );
    }

    /**
     * Set Order Line product
     * by product number
     * @param  mixed $orderLineHandle
     * @param  string $product
     * @return boolean
     */
    public function product($orderLineHandle, $product)
    {
        $products = new Product($this->client_raw);
        $productHandle = $products->getHandle($product);

        $this->client
            ->OrderLine_SetProduct(
                array(
                    'orderLineHandle' => $orderLineHandle,
                    'valueHandle' => $productHandle
                )
            );

        return true;
    }

    /**
     * Create Invoice line
     * and return handle
     * @param  mixed $orderHandle
     * @return object
     */
    public function create($orderHandle)
    {
        return $this->client
            ->OrderLine_Create(array('orderHandle' => $orderHandle))
            ->OrderLine_CreateResult;
    }

    /**
     * Set Quotation Line discount
     * @param  mixed $orderLineHandle
     * @param  float $discount
     * @return boolean
     */
    public function discount($orderLineHandle, $discount)
    {
        $this->client
            ->OrderLine_SetDiscountAsPercent(
                array(
                    'orderLineHandle' => $orderLineHandle,
                    'value'           => $discount
                )
            );

        return true;
    }

    /**
     * Set Quotation Line description
     * @param  mixed $orderLineHandle
     * @param  string $description
     * @return boolean
     */
    public function description($orderLineHandle, $description)
    {
        $this->client
            ->OrderLine_SetDescription(
                array(
                    'orderLineHandle' => $orderLineHandle,
                    'value'           => $description
                )
            );

        return true;
    }

    /**
     * Set Quotation Line price without VAT
     * @param  mixed $orderLineHandle
     * @param  float $price
     * @return boolean
     */
    public function price($orderLineHandle, $price)
    {
        $this->client
            ->OrderLine_SetUnitNetPrice(
                array(
                    'orderLineHandle' => $orderLineHandle,
                    'value'           => $price
                )
            );

        return true;
    }

    /**
     * Set Quotation Line quantity
     * @param  mixed $orderLineHandle
     * @param  float $qty
     * @return boolean
     */
    public function qty($orderLineHandle, $qty)
    {
        $this->client
            ->OrderLine_SetQuantity(
                array(
                    'orderLineHandle' => $orderLineHandle,
                    'value'           => $qty
                )
            );

        return true;
    }

    /**
     * Set Quotation Line unit
     * by unit number
     * @param  mixed $orderLineHandle [description]
     * @param  integer $unit
     * @return boolean
     */
    public function unit($orderLineHandle, $unit)
    {
        $units      = new Unit($this->client_raw);
        $unitHandle = $units->getHandle($unit);

        $this->client
            ->OrderLine_SetUnit(
                array(
                    'orderLineHandle' => $orderLineHandle,
                    'valueHandle'     => $unitHandle
                )
            );

        return true;
    }

}