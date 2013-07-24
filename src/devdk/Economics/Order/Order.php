<?php namespace devdk\Economics\Order;

use devdk\Economics\Client;
use devdk\Economics\Debtor\Debtor;
use Closure;
use Exception;

class Order {

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
     * Get Order handle by number
     * @param  integer $no
     * @return object
     */
    public function getHandle($no)
    {
        if( is_object($no) AND isset($no->Number) ) return $no;

        if( @$result = $this->client
                ->Order_FindByNumber(array('number'=>$no))
                ->Order_FindByNumberResult
        ) return $result;
    }

    /**
     * Get Orders from handles
     * @param  object $handels
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->Order_GetDataArray(array('entityHandles'=>array('OrderHandle'=>$handles)))
            ->Order_GetDataArrayResult
            ->OrderData;
    }

    /**
     * Get all Orders
     * @return array
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
     * Get specific Order by
     * Order number
     * @param  integer $no
     * @return object
     */
    public function get($no)
    {
        $handle = $this->getHandle($no);

        return $this->getArrayFromHandles($handle);
    }

    /**
     * Get all current Orders
     * @return array
     */
    public function current()
    {
        $handles = $this->client
            ->Order_GetAllCurrent()
            ->Order_GetAllCurrentResult
            ->OrderHandle;

        return $this->getArrayFromHandles( $handles );
    }

    /**
     * Get Order debtor from order number
     * @param  integer $no
     * @return object
     */
    public function debtor($no)
    {
        $handle = $this->getHandle($no);

        $debtorHandle = $this->client
            ->Order_GetDebtor(array('orderHandle'=>$handle))
            ->Order_GetDebtorResult;

        $debtor = new Debtor($this->client_raw);

        return $debtor->getArrayFromHandles(array('DebtorHandle'=>$debtorHandle));
    }

    /**
     * Get/Set sent status of a Order.
     * @param  integer $no
     * @param  boolean $sent
     * @return boolean
     */
    public function sent($no, $sent=NULL)
    {
        $handle = array('orderHandle'=>$this->getHandle($no));

        if( is_null($sent) )
        {
            return $this->client
                ->Order_GetIsSent($handle)
                ->Order_GetIsSentResult;
        }

        if( !!$sent )
        {
            $this->client
                ->Order_RegisterAsSent($handle);
        }
        else
        {
            $this->client
                ->Order_CancelSentStatus($handle);
        }

        return true;
    }

    /**
     * Get Order due date
     * @return string
     */
    public function due($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Order_GetDueDate(array('orderHandle'=>$handle))
            ->Order_GetDueDateResult;
    }

    /**
     * Get a Order total with or without VAT
     * @param  integer  $no
     * @param  boolean  $vat
     * @return float
     */
    public function total($no, $vat=false)
    {
        $handle = $this->getHandle($no);

        $request = array('orderHandle'=>$handel);

        if( $vat )
        {
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
     * @param  integer  $no
     * @return boolean
     */
    public function isArchived($no)
    {
        $handle = $this->getHandle($no);

        $this->client
            ->Order_GetIsArchived(array('orderHandle'=>$handle))
            ->Order_GetIsArchivedResult;
    }

    /**
     * Get lines of a specific Order
     * @param  integer $no
     * @return array
     */
    public function lines($no)
    {
        $handle = $this->getHandle($no);

        $lineHandles = $this->client
            ->Order_GetLines(array('orderHandle'=>$handle))
            ->Order_GetLinesResult
            ->OrderLineHandle;

        $line = new Line($this->client_raw);

        return $line->getArrayFromHandles($lineHandles);
    }

    /**
     * Get Order PDF by number
     * @param  integer $no
     * @param  boolean $download [description]
     * @return mixed
     */
    public function pdf($no, $download=false)
    {
        $handle = $this->getHandle($no);

        $pdf = $this->client
            ->Order_GetPdf(array('orderHandle'=>$handle))
            ->Order_GetPdfResult;

        if( $download )
        {
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="'.$no.'.pdf"');
            echo $pdf;
            return true;
        }

        return $pdf;
    }

    /**
     * Create new Order
     * @param  integer  $debtorNumber
     * @param  Closure  $callback
     * @return object
     */
    public function create($debtorNumber, Closure $callback)
    {
        $debtor = new Debtor($this->client_raw);
        $debtorHandle = $debtor->getHandle($debtorNumber);

        $orderHandle = $this->client
            ->Order_Create(array('debtorHandle'=>$debtorHandle))
            ->Order_CreateResult;


        if( !$orderHandle->Id )
        {
            throw new Exception("Error: creating Invoice.");
        }

        $this->lines = new Line($this->client_raw, $orderHandle);

        call_user_func($callback, $this->lines);

        return $this->client->Order_GetDataArray(
            array('entityHandles' => array('OrderHandle' => $orderHandle))
        )->Order_GetDataArrayResult;
    }
}