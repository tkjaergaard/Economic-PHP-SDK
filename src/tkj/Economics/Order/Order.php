<?php

namespace tkj\Economics\Order;

use tkj\Economics\Client;
use tkj\Economics\Debtor\Debtor;
use Closure;
use Exception;

class Order
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
     * Construct class and set dependencies
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;
    }

    /**
     * Get Order handle by number
     *
     * @param integer $no
     * @return null|object|integer
     */
    public function getHandle($no)
    {
        if (is_object($no) AND isset($no->Number)) {
            return $no;
        }

        @$result = $this->client
            ->Order_FindByNumber([
                'number' => $no,
            ])
            ->Order_FindByNumberResult;

        if($result) {
            return $result;
        }

        return null;
    }

    /**
     * Get Orders from handles
     *
     * @param object $handles
     * @return object[]|array
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->Order_GetDataArray([
                'entityHandles' => [
                    'OrderHandle' => $handles,
                ],
            ])
            ->Order_GetDataArrayResult
            ->OrderData;
    }

    /**
     * Get all Orders
     *
     * @return object[]|array
     */
    public function all()
    {
        $handles = $this->client
            ->Order_GetAll()
            ->Order_GetAllResult
            ->OrderHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Get specific Order by Order number
     *
     * @param integer $no
     * @return \object[]
     */
    public function get($no)
    {
        $handle = $this->getHandle($no);
        return $this->getArrayFromHandles($handle);
    }

    /**
     * Get all current Orders
     *
     * @return array|\object[]
     */
    public function current()
    {
        $handles = $this->client
            ->Order_GetAllCurrent()
            ->Order_GetAllCurrentResult
            ->OrderHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Get Order debtor from order number
     *
     * @param integer $no
     * @return object
     */
    public function debtor($no)
    {
        $handle = $this->getHandle($no);

        $debtorHandle = $this->client
            ->Order_GetDebtor([
                'orderHandle' => $handle,
            ])
            ->Order_GetDebtorResult;

        $debtor = new Debtor($this->client_raw);

        return $debtor->getArrayFromHandles([
            'DebtorHandle' => $debtorHandle,
        ]);
    }

    /**
     * Get/Set sent status of a Order.
     *
     * @param  integer $no
     * @param  boolean $sent
     * @return boolean
     */
    public function sent($no, $sent = null)
    {
        $handle = [
            'orderHandle' => $this->getHandle($no),
        ];

        if (is_null($sent)) {
            return $this->client
                ->Order_GetIsSent($handle)
                ->Order_GetIsSentResult;
        }

        if (!!$sent) {
            $this->client
                ->Order_RegisterAsSent($handle);

            return true;
        }

        $this->client
            ->Order_CancelSentStatus($handle);

        return true;
    }

    /**
     * Get Order due date
     *
     * @param integer $no
     * @return object
     */
    public function due($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Order_GetDueDate([
                'orderHandle' => $handle,
            ])
            ->Order_GetDueDateResult;
    }

    /**
     * Get a Order total with or without VAT
     *
     * @param $no
     * @param bool|false $vat
     * @return mixed
     */
    public function total($no, $vat=false)
    {
        $handle = $this->getHandle($no);

        $request = [
            'orderHandle' => $handle
        ];

        if ($vat) {
            return $this->client
                ->Order_GetGrossAmount($request)
                ->Order_GetGrossAmountResult;
        }

        return $this->client
            ->Order_GetNetAmount($request)
            ->Order_GetNetAmountResult;

    }

    /**
     * Get if a Orders is archived
     *
     * @param integer $no
     * @return boolean
     */
    public function isArchived($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Order_GetIsArchived([
                'orderHandle' => $handle,
            ])
            ->Order_GetIsArchivedResult;
    }

    /**
     * Get lines of a specific Order
     *
     * @param integer $no
     * @return object
     */
    public function lines($no)
    {
        $handle = $this->getHandle($no);

        $lineHandles = $this->client
            ->Order_GetLines([
                'orderHandle' => $handle,
            ])
            ->Order_GetLinesResult
            ->OrderLineHandle;

        $line = new Line($this->client_raw);

        return $line->getArrayFromHandles($lineHandles);
    }

    /**
     * Get Order PDF by number
     *
     * @param integer $no
     * @param bool|false $download
     * @return bool
     */
    public function pdf($no, $download = false)
    {
        $handle = $this->getHandle($no);

        $pdf = $this->client
            ->Order_GetPdf([
                'orderHandle' => $handle,
            ])
            ->Order_GetPdfResult;

        if ($download) {
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $no . '.pdf"');
            echo $pdf;
            return true;
        }

        return $pdf;
    }

    /**
     * Create new Order
     *
     * @param integer $debtorNumber
     * @param Closure $callback
     * @param array|null $options
     * @return mixed
     * @throws Exception
     */
    public function create($debtorNumber, Closure $callback, array $options = null)
    {
        $debtor = new Debtor($this->client_raw);
        $debtorHandle = $debtor->getHandle($debtorNumber);

        $orderHandle = $this->client
            ->Order_Create([
                'debtorHandle' => $debtorHandle,
            ])
            ->Order_CreateResult;


        if(!$orderHandle->Id) {
            throw new Exception("Error: creating Invoice");
        }

        if ($options) {
            $this->setOptions($orderHandle, $options);
        }

        $this->lines = new Line($this->client_raw, $orderHandle);

        call_user_func($callback, $this->lines);

        return $this->client->Order_GetDataArray([
            'entityHandles' => [
                'OrderHandle' => $orderHandle,
            ],
        ])->Order_GetDataArrayResult;
    }

    /**
     * Set Order Option
     *
     * @param object $handle
     * @param array $options
     */
    public function setOptions($handle, array $options)
    {
        foreach($options as $option => $value) {
            switch(strtolower($option)) {
                case 'vat':
                    $this->client
                        ->Order_SetIsVatIncluded([
                            'orderHandle' => $handle,
                            'value'       => $value,
                        ]);
                    break;

                case 'text1':
                    $this->client
                        ->Order_SetTextLine1([
                            'orderHandle' => $handle,
                            'value'       => $value,
                        ]);
                    break;
                case 'termsofdelivery':
                    $this->client
                        ->Order_SetTermsOfDelivery([
                            'orderHandle' => $handle,
                            'value'       => $value,
                        ]);
                    break;
                case 'deliveryaddress':
                    $this->client
                        ->Order_SetDeliveryAddress([
                            'orderHandle' => $handle,
                            'value'       => $value,
                        ]);
                    break;
                case 'deliverycity':
                    $this->client
                        ->Order_SetDeliveryCity([
                            'orderHandle' => $handle,
                            'value'       => $value,
                        ]);
                    break;
                case 'deliverycountry':
                    $this->client
                        ->Order_SetDeliveryCountry([
                            'orderHandle' => $handle,
                            'value'       => $value
                        ]);
                    break;
                case 'deliverypostalcode':
                    $this->client
                        ->Order_SetDeliveryPostalCode([
                            'orderHandle' => $handle,
                            'value'       => $value
                        ]);
                     break;
                case 'otherreference':
                    $this->client
                        ->Order_SetOtherReference([
                            'orderHandle' => $handle,
                            'value'       => $value
                        ]);
                    break;
                case 'date':
                    $this->client
                        ->Order_SetDate([
                            'orderHandle' => $handle,
                            'value'       => $value
                        ]);
                    break;
                case 'layout':
                    $this->client
                        ->Order_SetLayout([
                            'orderHandle' => $handle,
                            'value'       => $value
                        ]);
                    break;
            }
        }
    }

    /**
     * Upgrade a Order to a Invoice
     *
     * @param  integer $orderNumber
     * @return object
     */
    public function upgrade($orderNumber)
    {
        $handle = $this->getHandle($orderNumber);

        $id = $this->client
            ->Order_UpgradeToInvoice([
                'orderHandle' => $handle,
            ])
            ->Order_UpgradeToInvoiceResult;

        return $id;
    }

    /**
     * Delete order
     *
     * @param integer $no
     * @return bool
     */
    public function delete($no)
    {
        $handle = $this->getHandle($no);

        try {
            $this->client
                ->Order_Delete([
                    'OrderHandle' => $handle,
                ]);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }
}
