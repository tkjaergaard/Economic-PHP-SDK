<?php

namespace tkj\Economics\Debtor;

use tkj\Economics\Client;
use Exception;

class Contact
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
     * Construct class and set dependencies
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client     = $client->getClient();
        $this->client_raw = $client;
    }

    /**
     * Get Contacts from handles
     *
     * @param object[] $handles
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->DebtorContact_GetDataArray([
                'entityHandles' => [
                    'DebtorContactHandle' => $handles,
                ],
            ])
            ->DebtorContact_GetDataArrayResult
            ->DebtorContactData;
    }

    /**
     * Get all Contacts
     *
     * @return array|object[]
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
            ->DebtorContact_GetData([
                'entityHandle' => [
                    'Id' => $id
                ],
            ])->DebtorContact_GetDataResult;

        return $data;
    }

    /**
     * Search contact by full name
     *
     * @param  string $name
     * @return array
     */
    public function search($name)
    {
        $handles = $this->client
            ->DebtorContact_FindByName([
                'name' => $name,
            ])
            ->DebtorContact_FindByNameResult
            ->DebtorContactHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Update an existion Contact by Contact ID
     *
     * @param array $data
     * @param integer $id
     * @return object
     * @throws Exception
     */
    public function update(array $data, $id)
    {
        if (!is_integer($id)) {
            throw new Exception("ID must be a integer");
        }

        $handle = [
            'Id' => $id
        ];

        foreach ($data as $field => $value) {
            switch (strtolower($field)) {
                case 'name':
                    $this->client
                        ->debtorContact_SetName([
                            'debtorContactHandle' => $handle,
                            'value'               => $value
                        ]);
                    break;

                case 'email':
                    $this->client
                        ->debtorContact_SetEmail([
                            'debtorContactHandle' => $handle,
                            'value'               => $value
                        ]);
                    break;

                case 'phone':
                    $this->client
                        ->DebtorContact_SetTelephoneNumber([
                            'debtorContactHandle' => $handle,
                            'value'               => $value
                        ]);
                    break;

                case 'invoice':
                    $this->client
                        ->debtorContact_SetIsToReceiveEmailCopyOfInvoice([
                            'debtorContactHandle' => $handle,
                            'value'               => !!$value
                        ]);
                    break;

                case 'order':
                    $this->client
                        ->debtorContact_SetIsToReceiveEmailCopyOfOrder([
                            'debtorContactHandle' => $handle,
                            'value'               => !!$value
                        ]);
                    break;

                case 'comment':
                    $this->client
                        ->debtorContact_SetComments([
                            'debtorContactHandle' => $handle,
                            'value'               => $value
                        ]);
                    break;
            }
        }

        return $this->getArrayFromHandles($handle);
    }

    /**
     * Create a new Contact from data array
     *
     * @param  array   $data
     * @param  integer $debtor
     * @return array
     */
    public function create(array $data, $debtor)
    {
        $debtors = new Debtor($this->client_raw);
        $debtorHandle = $debtors->getHandle($debtor);

        $id = $this->client
            ->DebtorContact_Create([
                'debtorHandle' => $debtorHandle,
                'name'         => $data['name']
            ])
            ->DebtorContact_CreateResult;

        return $this->update($data, $id->Id);
    }

    /**
     * Delete a Contact by ID
     *
     * @param  integer $id
     * @return boolean
     */
    public function delete($id)
    {
        $data = $this->findById($id);

        $this->client
            ->DebtorContact_Delete([
                'debtorContactHandle' => $data->Handle,
            ]);

        return true;
    }
}
