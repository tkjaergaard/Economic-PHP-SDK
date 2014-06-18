<?php namespace tkj\Economics\Debtor;

use tkj\Economics\Client;
use tkj\Economics\Account\Account;

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
     * Get Debtor Group handle by number
     * @param  integer $no
     * @return object
     */
    public function getHandle($no)
    {
        if( is_object($no) AND isset($no->Number) ) return $no;

        return $this->client
            ->DebtorGroup_FindByNumber(array('number'=>$no))
            ->DebtorGroup_FindByNumberResult;
    }

    /**
     * Get Debtor Groups from handles
     * @param  object $handels
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->DebtorGroup_GetDataArray(array('entityHandles'=>$handles))
            ->DebtorGroup_GetDataArrayResult
            ->DebtorGroupData;
    }

    public function get($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->DebtorGroup_GetData(array('entityHandle'=> $handle))
            ->DebtorGroup_GetDataResult;
    }

    /**
     * Get all Debtor Groups
     * @return object
     */
    public function all()
    {
        $handles = $this->client
            ->DebtorGroup_GetAll()
            ->DebtorGroup_GetAllResult
            ->DebtorGroupHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Get all Debtors of a debtor group
     * by group number
     * @param  [type] $no [description]
     * @return [type]     [description]
     */
    public function debtors($no)
    {
        $handle = $this->getHandle($no);

        $debtorHandles = $this->client
            ->DebtorGroup_GetDebtors(array('debtorGroupHandle'=>$handle))
            ->DebtorGroup_GetDebtorsResult
            ->DebtorHandle;

        $debtor = new Debtor($this->client_raw);
        return $debtor->getArrayFromHandles($debtorHandles);
    }

    /**
     * Create a new Debtor Group
     * @param  string  $name
     * @param  integer $account
     * @return object
     */
    public function create($name, $account)
    {
        $all    = $this->all();
        $number = end($all)->Number + 1;

        $accounts = new Account($this->client_raw);
        $accountHandle = $accounts->getHandle($account);

        $groupHandle = $this->client
            ->DebtorGroup_Create(array(
                "number"        => $number,
                "name"          => $name,
                "accountHandle" => $accountHandle
            ))->DebtorGroup_CreateResult;

        return $this->get($number);
    }

}