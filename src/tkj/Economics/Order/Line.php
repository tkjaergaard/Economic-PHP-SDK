<?php

namespace tkj\Economics\Order;

use tkj\Economics\ClientInterface as Client;
use tkj\Economics\Unit\Unit;
use tkj\Economics\Product\Product;

class Line
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

    /**
     * Order handle for creating
     * or editing lines
     * @var object
     */
    protected $orderHandle;

    /**
     * Construct class and set dependencies
     *
     * @param Client $client
     * @param null $orderHandle
     */
    public function __construct(Client $client, $orderHandle = null)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;

        if ($orderHandle) {
            $this->orderHandle = $orderHandle;
        }
    }

    /**
     * Set Order handle for editing or creating lines
     *
     * @param $handle
     * @return $this
     */
    public function setHandle($handle)
    {
        $this->orderHandle = $handle;
        return $this;
    }

    /**
     * Get Order Lines from handles
     *
     * @param object[] $handles
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
         return $this->client
            ->OrderLine_GetDataArray([
                'entityHandles' => $handles
            ])
            ->OrderLine_GetDataArrayResult->OrderLineData;
    }

    /**
     * Add Order Line
     *
     * @param array $data
     * @return object
     */
    public function add(array $data)
    {
        $defaults = [
            "description" => null,
            "price"       => null,
            "discount"    => null,
            "qty"         => 1,
            "unit"        => null
        ];

        $merged = array_merge($defaults, $data);

        $line = $this->create($this->orderHandle);

        if (isset($merged['product'])) {
            $this->product($line, $merged['product']);

            unset($merged['product']);
        }

        return $this->update($data, $line);
    }

    /**
     * Update Order Line by data
     *
     * @param array $data
     * @param object $line
     * @return object
     */
    public function update(array $data, $line)
    {
        if (is_integer($line)) {
            $line = [
                'Id' => $line
            ];
        }

        foreach ($data as $name => $value) {
            if (is_null($value)) {
                continue;
            }

            switch (strtolower($name)) {
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

        return $this->getArrayFromHandles([
            'OrderLineHandle' => $line,
        ]);
    }

    /**
     * Set Order Line product by product number
     *
     * @param object $orderLineHandle
     * @param $product
     * @return bool
     */
    public function product($orderLineHandle, $product)
    {
        $products = new Product($this->client_raw);
        $productHandle = $products->getHandle($product);

        $this->client
            ->OrderLine_SetProduct([
                'orderLineHandle' => $orderLineHandle,
                'valueHandle' => $productHandle
            ]);

        return true;
    }

    /**
     * Create Invoice line and return handle
     *
     * @param object $orderHandle
     * @return object
     */
    public function create($orderHandle)
    {
        return $this->client
            ->OrderLine_Create([
                'orderHandle' => $orderHandle,
            ])
            ->OrderLine_CreateResult;
    }

    /**
     * Set Quotation Line discount
     *
     * @param $orderLineHandle
     * @param $discount
     * @return bool
     */
    public function discount($orderLineHandle, $discount)
    {
        $this->client
            ->OrderLine_SetDiscountAsPercent([
                'orderLineHandle' => $orderLineHandle,
                'value'           => $discount
            ]);

        return true;
    }

    /**
     * Set Quotation Line description
     *
     * @param $orderLineHandle
     * @param $description
     * @return bool
     */
    public function description($orderLineHandle, $description)
    {
        $this->client
            ->OrderLine_SetDescription([
                'orderLineHandle' => $orderLineHandle,
                'value'           => $description
            ]);

        return true;
    }

    /**
     * Set Quotation Line price without VAT
     *
     * @param $orderLineHandle
     * @param $price
     * @return bool
     */
    public function price($orderLineHandle, $price)
    {
        $this->client
            ->OrderLine_SetUnitNetPrice([
                'orderLineHandle' => $orderLineHandle,
                'value'           => $price
            ]);

        return true;
    }

    /**
     * Set Quotation Line quantity
     *
     * @param $orderLineHandle
     * @param $qty
     * @return bool
     */
    public function qty($orderLineHandle, $qty)
    {
        $this->client
            ->OrderLine_SetQuantity([
                'orderLineHandle' => $orderLineHandle,
                'value'           => $qty
            ]);

        return true;
    }

    /**
     * Set Quotation Line unit by unit number
     *
     * @param object $orderLineHandle
     * @param $unit
     * @return bool
     */
    public function unit($orderLineHandle, $unit)
    {
        $units      = new Unit($this->client_raw);
        $unitHandle = $units->getHandle($unit);

        $this->client
            ->OrderLine_SetUnit([
                'orderLineHandle' => $orderLineHandle,
                'valueHandle'     => $unitHandle
            ]);

        return true;
    }
}
