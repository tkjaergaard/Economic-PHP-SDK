<?php namespace tkj\Economics\Quotation;

use tkj\Economics\ClientInterface as Client;
use tkj\Economics\Debtor\Debtor;
use tkj\Economics\Quotation\QuotationLines;
use Exception;
use Closure;

class Quotation
{

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
        $this->client = $client->getClient();
        $this->client_raw = $client;
    }

    /**
     * Get all Quotations
     * @return arrat
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
     * @param  object $handels
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        $quotations = $this->client
            ->Quotation_GetDataArray(array('entityHandles' => $handles))
            ->Quotation_GetDataArrayResult
            ->QuotationData;

        return $quotations;
    }

    /**
     * Delete Quotation by
     * Quotation number
     * @param  integer $no
     * @return boolean
     */
    public function delete($no)
    {
        $handle = $this->getHandle($no);

        $this->client->Quotation_Delete(array('quotationHandle' => $handle));

        return true;
    }

    /**
     * Get Quotation due date
     * by Quotation number
     * @param  integer $no
     * @return mixed
     */
    public function due($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Quotation_GetDueDate(array('quotationHandle' => $handle))
            ->Quotation_GetDueDateResult;
    }

    /**
     * Get specific Quotation by number
     * @param  integer $no
     * @return mixed
     */
    public function get($no)
    {
        $handle = $this->getHandle($no);
        return $this->client
            ->Quotation_GetData(array('entityHandle' => $handle))
            ->Quotation_GetDataResult;
    }

    /**
     * Get all Quotation lines
     * by Quotations number
     * @param  integer $no
     * @return mixed
     */
    public function lines($no)
    {
        $handle = $this->getHandle($no);

        $handles = $this->client
            ->Quotation_GetLines(array('quotationHandle' => $handle))
            ->Quotation_GetLinesResult;

        return $this->client
            ->QuotationLine_GetDataArray(array('entityHandles' => $handles))
            ->QuotationLine_GetDataArrayResult
            ->QuotationLineData;
    }

    /**
     * Get Quotation pdf
     * by Quotation number
     * @param  integer $no
     * @return mixed
     */
    public function pdf($no, $download = false)
    {
        $handle = $this->getHandle($no);
        $pdf = $this->client
            ->Quotation_GetPdf(array('quotationHandle' => $handle))
            ->Quotation_GetPdfResult;

        if ($download) {
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $no . '.pdf"');
            echo $pdf;
            return true;
        }

        return $pdf;
    }

    /**
     * Get or set sent status for a
     * Quotation by Quotation number
     * @param  integer $no
     * @param  boolean $set
     * @return boolean
     */
    public function sent($no, $set = NULL)
    {
        $handle = $this->getHandle($no);

        if (!is_bool($set)) {
            return $this->client
                ->Quotation_GetIsSent(array('quotationHandle' => $handle))
                ->Quotation_GetIsSentResult;

        } else if (!$set) {
            $this->client->Quotation_CancelSentStatus(array('quotationHandle' => $handle));
        } else {
            $this->client->Quotation_RegisterAsSent(array('quotationHandle' => $handle));
        }

        return true;
    }

    /**
     * Get Quotation total
     * by Quotation number
     * @param  integer $no
     * @return mixed
     */
    public function total($no)
    {
        $handle = $this->getHandle($no);
        return $this->client
            ->Quotation_GetNetAmount(array('quotationHandle' => $handle))
            ->Quotation_GetNetAmountResult;
    }

    /**
     * Upgrade a Quotation to an order
     * by Quotation number and returns the
     * ID of the order
     * @param  integer $no
     * @return integer      return the ID of the Order
     */
    public function upgrade($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Quotation_UpgradeToOrder(array('quotationHandle' => $handle))
            ->Quotation_UpgradeToOrderResult;
    }

    /**
     * Create a new Quotaion to a specific Debtor
     * @param  integer $debtorNumber
     * @param  Closure $callback
     * @return object
     */
    public function create($debtorNumber, Closure $callback)
    {
        $debtor = new Debtor($this->client_raw);
        $debtorHandle = $debtor->getHandle($debtorNumber);

        $quotationHandle = $this->client
            ->Quotation_Create(array('debtorHandle' => $debtorHandle))
            ->Quotation_CreateResult;


        if (!$quotationHandle->Id) {
            throw new Exception("Error: creating Quotation.");
        }

        $this->lines = new QuotationLines($this->client_raw, $quotationHandle);

        call_user_func($callback, $this->lines);

        return $this->get($quotationHandle);
    }

    /**
     * Get Quotation handle by number
     * @param  integer $no
     * @return object
     */
    public function getHandle($no)
    {
        if (is_object($no) AND isset($no->Id)) return $no;

        return $this->client
            ->Quotation_FindByNumber(array('number' => $no))
            ->Quotation_FindByNumberResult;
    }

}