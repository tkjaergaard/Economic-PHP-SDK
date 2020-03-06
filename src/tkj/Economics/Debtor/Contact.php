<?php namespace tkj\Economics\Debtor;

use tkj\Economics\ClientInterface as Client;
use Exception;

class Contact {

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
     * Get Contacts from handles
     * @param  object $handels
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->DebtorContact_GetDataArray(array('entityHandles'=>array('DebtorContactHandle'=>$handles)))
            ->DebtorContact_GetDataArrayResult
            ->DebtorContactData;
    }

    /**
     * Get all Contacts
     * @return array
     */
    public function all()
    {
        $handles = $this->client
            ->DebtorContact_GetAll()
            ->DebtorContact_GetAllResult
            ->DebtorContactHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Get Contact data by ID
     * @param  integer $id
     * @return object
     */
    public function findById($id)
    {
        $data = $this->client
            ->DebtorContact_GetData(array(
                'entityHandle' => array('Id' => $id)
            ))->DebtorContact_GetDataResult;

        return $data;
    }

    /**
     * Search contact by full name
     * @param  string $name
     * @return array
     */
    public function search($name)
    {
        $handles = $this->client
            ->DebtorContact_FindByName(array('name'=>$name))
            ->DebtorContact_FindByNameResult
            ->DebtorContactHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Update an existion Contact by Contact ID
     * @param  array   $data
     * @param  integer $id
     * @return array
     */
    public function update(array $data, $id)
    {
        if( !is_integer($id) )
            throw new Exception("ID must be a integer");

        $handle = array('Id'=>$id);

        foreach( $data as $field => $value )
        {
            switch( strtolower($field) )
            {
                case 'name':
                    $this->client
                        ->debtorContact_SetName(array(
                            'debtorContactHandle' => $handle,
                            'value'               => $value
                        ));
                    break;

                case 'email':
                    $this->client
                        ->debtorContact_SetEmail(array(
                            'debtorContactHandle' => $handle,
                            'value'               => $value
                        ));
                    break;

                case 'phone':
                    $this->client
                        ->DebtorContact_SetTelephoneNumber(array(
                            'debtorContactHandle' => $handle,
                            'value'               => $value
                        ));
                    break;

                case 'invoice':
                    $this->client
                        ->debtorContact_SetIsToReceiveEmailCopyOfInvoice(array(
                            'debtorContactHandle' => $handle,
                            'value'               => !!$value
                        ));
                    break;

                case 'order':
                    $this->client
                        ->debtorContact_SetIsToReceiveEmailCopyOfOrder(array(
                            'debtorContactHandle' => $handle,
                            'value'               => !!$value
                        ));
                    break;

                case 'comment':
                    $this->client
                        ->debtorContact_SetComments(array(
                            'debtorContactHandle' => $handle,
                            'value'               => $value
                        ));
                    break;

            }
        }

        return $this->getArrayFromHandles($handle);
    }

    /**
     * Create a new Contact from data array
     * @param  array   $data
     * @param  integer $debtor
     * @return array
     */
    public function create(array $data, $debtor)
    {
        $debtors = new Debtor($this->client_raw);
        $debtorHandle = $debtors->getHandle($debtor);

        $id = $this->client
            ->DebtorContact_Create(array(
                'debtorHandle' => $debtorHandle,
                'name'         => $data['name']
            ))->DebtorContact_CreateResult;

        return $this->update($data, $id->Id);
    }

    /**
     * Delete a Contact by ID
     * @param  integer $id
     * @return boolean
     */
    public function delete($id)
    {
        $data = $this->findById($id);

        $this->client
            ->DebtorContact_Delete(array(
                "debtorContactHandle" => $data->Handle
            ));

        return true;
    }

}
