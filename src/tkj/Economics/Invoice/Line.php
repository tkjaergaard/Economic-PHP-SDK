<?php namespace tkj\Economics\Invoice;

use tkj\Economics\ClientInterface as Client;
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
     * Invoice Handle used
     * when manipluation invoice lines
     * @var object
     */
    protected $invoiceHandle;

    /**
     * Construct class and set dependencies
     * @param devdk\Economics\Client $client
     */
    public function __construct(Client $client, $invoiceHandle=NULL)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;

        if( $invoiceHandle )
        {
            $this->invoiceHandle = $invoiceHandle;
        }
    }

    /**
     * Get Invoice Lines from handles
     * @param  object $handels
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->CurrentInvoiceLine_GetDataArray(array('entityHandles'=>$handles))
            ->CurrentInvoiceLine_GetDataArrayResult
            ->CurrentInvoiceLineData;
    }

    /**
     * Set Invoice handle for
     * editing or creating lines
     * @param object $handle
     */
    public function setHandle($handle)
    {
        $this->invoiceHandle = $handle;
    }

    /**
     * Add Invoice line
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

        $line = $this->create($this->invoiceHandle);

        if( isset($merged['product']) )
        {
            $this->product($line, $merged['product']);
            unset( $merged['product'] );
        }

        return $this->update($data, $line);
    }

    /**
     * Update Invoice Line with data
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

        return $this->getArrayFromHandles( array('CurrentInvoiceLineHandle'=>$line) );
    }

    /**
     * Create Invoice line
     * and return handle
     * @param  mixed $invoiceHandle
     * @return object
     */
    public function create($invoiceHandle)
    {
        return $this->client
            ->CurrentInvoiceLine_Create(array('invoiceHandle' => $invoiceHandle))
            ->CurrentInvoiceLine_CreateResult;
    }

    /**
     * Set Invoice Line product
     * by product number
     * @param  mixed $invoiceLineHandle
     * @param  integer $product
     * @return boolean
     */
    public function product($invoiceLineHandle, $product)
    {
        $products = new Product($this->client_raw);
        $productHandle = $products->getHandle($product);

        $this->client
            ->CurrentInvoiceLine_SetProduct(
                array(
                    'currentInvoiceLineHandle' => $invoiceLineHandle,
                    'valueHandle' => $productHandle
                )
            );

        return true;
    }

    /**
     * Set Quotation Line discount
     * @param  mixed $QuotationLineHandle
     * @param  float $discount
     * @return boolean
     */
    public function discount($invoiceLineHandle, $discount)
    {
        $this->client
            ->CurrentInvoiceLine_SetDiscountAsPercent(
                array(
                    'currentInvoiceLineHandle' => $invoiceLineHandle,
                    'value'                    => $discount
                )
            );

        return true;
    }

    /**
     * Set Quotation Line description
     * @param  mixed $QuotationLineHandle
     * @param  string $description
     * @return boolean
     */
    public function description($invoiceLineHandle, $description)
    {
        $this->client
            ->CurrentInvoiceLine_SetDescription(
                array(
                    'currentInvoiceLineHandle' => $invoiceLineHandle,
                    'value'                    => $description
                )
            );

        return true;
    }

    /**
     * Set Quotation Line price without VAT
     * @param  mixed $QuotationLineHandle
     * @param  float $price
     * @return boolean
     */
    public function price($invoiceLineHandle, $price)
    {
        $this->client
            ->CurrentInvoiceLine_SetUnitNetPrice(
                array(
                    'currentInvoiceLineHandle' => $invoiceLineHandle,
                    'value'                    => $price
                )
            );

        return true;
    }

    /**
     * Set Quotation Line quantity
     * @param  mixed $QuotationLineHandle
     * @param  integer $qty
     * @return boolean
     */
    public function qty($invoiceLineHandle, $qty)
    {
        $this->client
            ->CurrentInvoiceLine_SetQuantity(
                array(
                    'currentInvoiceLineHandle' => $invoiceLineHandle,
                    'value'                    => $qty
                )
            );

        return true;
    }

    /**
     * Set Quotation Line unit
     * by unit number
     * @param  mixed $QuotationLineHandle [description]
     * @param  integer $unit
     * @return boolean
     */
    public function unit($invoiceLineHandle, $unit)
    {
        $units      = new Unit($this->client_raw);
        $unitHandle = $units->getHandle($unit);

        $this->client
            ->CurrentInvoiceLine_SetUnit(
                array(
                    'currentInvoiceLineHandle' => $invoiceLineHandle,
                    'valueHandle'              => $unitHandle
                )
            );

        return true;
    }

}