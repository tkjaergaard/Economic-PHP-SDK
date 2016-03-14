<?php

namespace tkj\Economics\Quotation;

use tkj\Economics\ClientInterface as Client;
use tkj\Economics\Debtor\Debtor;
use tkj\Economics\Quotation\QuotationLines;
use Exception;
use Closure;

class Quotation
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
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;
    }

    /**
     * Get Quotation handle by number
     *
     * @param  integer $no
     * @return object
     */
    public function getHandle($no)
    {
        if (is_object($no) and isset($no->Id)) {
            return $no;
        }

        return $this->client
            ->Quotation_FindByNumber([
                'number'=>$no,
            ])
            ->Quotation_FindByNumberResult;
    }

    /**
     * Get all Quotations
     *
     * @return array
     */
    public function all()
    {
        $handles = $this->client
            ->Quotation_GetAll()
            ->Quotation_GetAllResult
            ->QuotationHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Get Quotations from handles
     *
     * @param array $handles
     * @return mixed
     */
    public function getArrayFromHandles($handles)
    {
        $quotations = $this->client
            ->Quotation_GetDataArray([
                'entityHandles' => $handles,
            ])
            ->Quotation_GetDataArrayResult
            ->QuotationData;

        return $quotations;
    }

    /**
     * Delete Quotation by Quotation number
     *
     * @param  integer $no
     * @return boolean
     */
    public function delete($no)
    {
        $handle = $this->getHandle($no);

        $this->client->Quotation_Delete([
            'quotationHandle' => $handle
        ]);

        return true;
    }

    /**
     * Get Quotation due date by Quotation number
     *
     * @param  integer $no
     * @return mixed
     */
    public function due($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Quotation_GetDueDate([
                'quotationHandle' => $handle
            ])
            ->Quotation_GetDueDateResult;
    }

    /**
     * Get specific Quotation by number
     *
     * @param  integer $no
     * @return mixed
     */
    public function get($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Quotation_GetData([
                'entityHandle' => $handle,
            ])
            ->Quotation_GetDataResult;
    }

    /**
     * Get all Quotation lines by Quotations number
     *
     * @param  integer $no
     * @return mixed
     */
    public function lines($no)
    {
        $handle  = $this->getHandle($no);

        $handles = $this->client
            ->Quotation_GetLines([
                'quotationHandle' => $handle
            ])
            ->Quotation_GetLinesResult;

        return $this->client
            ->QuotationLine_GetDataArray([
                'entityHandles' => $handles
            ])
            ->QuotationLine_GetDataArrayResult
            ->QuotationLineData;
    }

    /**
     * Get Quotation pdf by Quotation number
     *
     * @param integer $no
     * @param bool|false $download
     * @return bool
     */
    public function pdf($no, $download = false)
    {
        $handle = $this->getHandle($no);
        $pdf = $this->client
            ->Quotation_GetPdf([
                'quotationHandle' => $handle,
            ])
            ->Quotation_GetPdfResult;

        if (!$download) {
            return $pdf;
        }

        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $no . '.pdf"');
        echo $pdf;

        return true;
    }

    /**
     * Get or set sent status for a Quotation by Quotation number
     *
     * @param  integer $no
     * @param  boolean $set
     * @return boolean
     */
    public function sent($no, $set = null)
    {
        $handle = $this->getHandle($no);

        if (!is_bool($set)) {
            return $this->client
                ->Quotation_GetIsSent([
                    'quotationHandle' => $handle,
                ])
                ->Quotation_GetIsSentResult;

        } elseif (!$set) {
            $this->client->Quotation_CancelSentStatus([
                'quotationHandle' => $handle,
            ]);
            return true;
        }

        $this->client->Quotation_RegisterAsSent([
            'quotationHandle' => $handle,
        ]);

        return true;
    }

    /**
     * Get Quotation total by Quotation number
     *
     * @param  integer $no
     * @return mixed
     */
    public function total($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Quotation_GetNetAmount([
                'quotationHandle' => $handle,
            ])
            ->Quotation_GetNetAmountResult;
    }

    /**
     * Upgrade a Quotation to an order by Quotation number and returns the ID of the order
     *
     * @param  integer $no
     * @return integer      return the ID of the Order
     */
    public function upgrade($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Quotation_UpgradeToOrder([
                'quotationHandle' => $handle,
            ])
            ->Quotation_UpgradeToOrderResult;
    }

    /**
     * Create a new Quotation to a specific Debtor
     *
     * @param integer $debtorNumber
     * @param Closure $callback
     * @return mixed
     * @throws Exception
     */
    public function create($debtorNumber, Closure $callback)
    {
        $debtor = new Debtor($this->client_raw);
        $debtorHandle = $debtor->getHandle($debtorNumber);

        $quotationHandle = $this->client
            ->Quotation_Create([
                'debtorHandle'=>$debtorHandle,
            ])
            ->Quotation_CreateResult;


        if (!$quotationHandle->Id) {
            throw new Exception("Error: creating Quotation.");
        }

        $this->lines = new QuotationLines($this->client_raw, $quotationHandle);

        call_user_func($callback, $this->lines);

        return $this->get($quotationHandle);
    }
}
