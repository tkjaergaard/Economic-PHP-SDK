<?php

namespace tkj\Economics\Quotation;

use tkj\Economics\Client;
use tkj\Economics\Unit\Unit;
use tkj\Economics\Product\Product;

class QuotationLines
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
     * Quotation handle
     *
     * @var mixed
     */
    protected $quotationHandle;

    /**
     * Construct class and set dependencies
     *
     * @param Client $client
     * @param null $quotationHandle
     */
    public function __construct(Client $client, $quotationHandle = null)
    {
        $this->client          = $client->getClient();
        $this->client_raw      = $client;
        $this->quotationHandle = $quotationHandle;
    }

    /**
     * Create new Quotation line from data
     *
     * @param array $data
     */
    public function add(array $data)
    {
        $defaults = [
            'description' => null,
            'price'       => null,
            'discount"'   => null,
            'qty'         => 1,
            'unit'        => null
        ];

        $merged = array_merge($defaults, $data);

        $line = $this->create($this->quotationHandle);

        if (isset($merged['product'])) {
            $this->product($line, $merged['product']);
            unset($merged['product']);
        }

        foreach ($merged as $name => $value) {
            if (is_null($value)) {
                continue;
            }

            switch ($name) {
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
    }

    /**
     * Create Qoutation line
     * and return handle
     * @param  mixed $quotationHandle
     * @return object
     */
    public function create($quotationHandle)
    {
        return $this->client
            ->QuotationLine_Create([
                'quotationHandle' => $quotationHandle,
            ])
            ->QuotationLine_CreateResult;
    }

    /**
     * Set Quotation Line discount
     *
     * @param  mixed $quotationLineHandle
     * @param  float $discount
     * @return boolean
     */
    public function discount($quotationLineHandle, $discount)
    {
        $this->client
            ->QuotationLine_SetDiscountAsPercent([
                'quotationLineHandle' => $quotationLineHandle,
                'value'               => $discount,
            ]);

        return true;
    }

    /**
     * Set Quotation Line description
     *
     * @param  mixed $quotationLineHandle
     * @param  string $description
     * @return boolean
     */
    public function description($quotationLineHandle, $description)
    {
        $this->client
            ->QuotationLine_SetDescription([
                'quotationLineHandle' => $quotationLineHandle,
                'value'               => $description,
            ]);

        return true;
    }

    /**
     * Set Quotation Line price without VAT
     *
     * @param  mixed $quotationLineHandle
     * @param  float $price
     * @return boolean
     */
    public function price($quotationLineHandle, $price)
    {
        $this->client
            ->QuotationLine_SetUnitNetPrice([
                'quotationLineHandle' => $quotationLineHandle,
                'value'               => $price,
            ]);

        return true;
    }

    /**
     * Set Quotation Line quantity
     *
     * @param  mixed $quotationLineHandle
     * @param  integer $qty
     * @return boolean
     */
    public function qty($quotationLineHandle, $qty)
    {
        $this->client
            ->QuotationLine_SetQuantity([
                'quotationLineHandle' => $quotationLineHandle,
                'value'               => $qty,
            ]);

        return true;
    }

    /**
     * Set Quotation Line unit by unit number
     *
     * @param  mixed $quotationLineHandle [description]
     * @param  integer $unit
     * @return boolean
     */
    public function unit($quotationLineHandle, $unit)
    {
        $units      = new Unit($this->client_raw);
        $unitHandle = $units->getHandle($unit);

        $this->client
            ->QuotationLine_SetUnit([
                'quotationLineHandle' => $quotationLineHandle,
                'valueHandle' => $unitHandle,
            ]);

        return true;
    }

    /**
     * Set Quotation Line product by product number
     *
     * @param  mixed $quotationLineHandle
     * @param  integer $product
     * @return boolean
     */
    public function product($quotationLineHandle, $product)
    {
        $products = new Product($this->client_raw);
        $productHandle = $products->getHandle($product);

        $this->client
            ->QuotationLine_SetProduct([
                'quotationLineHandle' => $quotationLineHandle,
                'valueHandle' => $productHandle,
            ]);

        return true;
    }
}
