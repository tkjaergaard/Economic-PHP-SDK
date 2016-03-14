<?php

namespace tkj\Economics\Debtor;

use Exception;
use tkj\Economics\Order\Order;
use tkj\Economics\Invoice\Invoice;
use tkj\Economics\Quotation\Quotation;
use tkj\Economics\ClientInterface as Client;

class Debtor
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
     * Get Debtor handle by number
     *
     * @param  string|integer $no
     * @return object
     */
    public function getHandle($no)
    {
        if (is_object($no) and isset($no->Number)) {
            return $no;
        }

        return $this->client
                    ->Debtor_FindByNumber([
                        'number' => $no,
                    ])
                    ->Debtor_FindByNumberResult;
    }

    /**
     * Get Debtor from handle
     *
     * @param  object $handle
     * @return object
     */
    public function getDataFromHandle($handle)
    {
        return $this->client
            ->Debtor_GetData([
                'entityHandle'=> $handle,
            ])
            ->Debtor_GetDataResult;
    }

    /**
     * Get Debtors from handles
     *
     * @param  object $handles
     * @return object
     */
    public function getArrayFromHandles($handles)
    {
        return $this->client
            ->Debtor_GetDataArray([
                'entityHandles'=>$handles,
            ])
            ->Debtor_GetDataArrayResult
            ->DebtorData;
    }

    /**
     * Get all Debtors
     *
     * @return array
     */
    public function all()
    {
        $handles = $this->client
            ->Debtor_GetAll()
            ->Debtor_GetAllResult
            ->DebtorHandle;

        return $this->getArrayFromHandles($handles);
    }

    /**
     * Get specific Debtor by number
     *
     * @param  integer $no
     * @return mixed
     */
    public function get($no)
    {
        $handle = $this->getHandle($no);
        return $this->getDataFromHandle($handle);
    }

    /**
     * Get Debtor address
     *
     * @param  integer $no
     * @return string
     */
    public function address($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Debtor_GetAddress([
                'debtorHandle'=>$handle,
            ])
            ->Debtor_GetAddressResult;
    }

    /**
     * Get Debtor balance
     *
     * @param  integer $no
     * @return float
     */
    public function balance($no)
    {
        $handle = $this->getHandle($no);

        return $this->client
            ->Debtor_GetBalance([
                'debtorHandle'=>$handle,
            ])
            ->Debtor_GetBalanceResult;
    }

    /**
     * Get all Debtor contacts by Debtor number
     *
     * @param  integer $no
     * @return object
     */
    public function contacts($no)
    {
        $handle = $this->getHandle($no);

        $contactHandles = $this->client
            ->Debtor_GetDebtorContacts([
                'debtorHandle'=>$handle,
            ])
            ->Debtor_GetDebtorContactsResult
            ->DebtorContactHandle;

        if (count($contactHandles) <= 0) {
            return [];
        }

        $contact = new Contact($this->client_raw);

        return $contact->getArrayFromHandles($contactHandles);
    }

    /**
     * Get all Debtor invoices by Debtor number
     *
     * @param  integer $no
     * @return array
     */
    public function invoices($no)
    {
        $handle = $this->getHandle($no);

        $invoiceHandles = $this->client
            ->Debtor_GetInvoices([
                'debtorHandle'=>$handle,
            ])
            ->Debtor_GetInvoicesResult
            ->InvoiceHandle;

        $invoice = new Invoice($this->client_raw);

        return $invoice->getArrayFromHandles($invoiceHandles);
    }

    /**
     * Get all Debtor Orders
     * @param  integer $no
     * @return object
     */
    public function orders($no)
    {
        $handle = $this->getHandle($no);

        $orderHandles = $this->client
            ->Debtor_GetOrders([
                'debtorHandle'=>$handle,
            ])
            ->Debtor_GetOrdersResult
            ->OrderHandle;

        $order = new Order($this->client_raw);
        return $order->getArrayFromHandles($orderHandles);
    }

    /**
     * Get all Debtor Quotation
     * @param  integer $no
     * @return object
     */
    public function quotations($no)
    {
        $handle = $this->getHandle($no);
        $quotationHandles = $this->client
            ->Debtor_GetQuotations([
                'debtorHandle'=>$handle,
            ])
            ->Debtor_GetQuotationsResult;

        if (count($quotationHandles) <= 0) {
            return [];
        }

        $quotationHandles = $quotationHandles->QuotationHandle;

        $quotation = new Quotation($this->client_raw);
        return $quotation->getArrayFromHandles($quotationHandles);
    }

    /**
     * Search Debtor by field
     *
     * @param string $value
     * @param string $by
     * @return array|mixed|object
     */
    public function search($value, $by = 'EMAIL')
    {
        $by = strtoupper($by);

        switch ($by) {
            case 'CI':
                return $this->findByCi($value);

            case 'EAN':
                return $this->findByEan($value);

            case 'EMAIL':
                return $this->findByEmail($value);

            case 'NAME':
                return $this->findByName($value);

            case 'PARTIALNAME':
                return $this->findByPartialName($value);

            case 'NUMBER':
                return $this->get($value);
        }
    }

    /**
     * Find Debtor by
     *
     * @param  integer $value
     * @return array
     */
    protected function findByCi($value)
    {
        $handles = $this->client
            ->Debtor_FindByCINumber([
                'ciNumber' => $value,
            ])
            ->Debtor_FindByCINumberResult
            ->DebtorHandle;

        $result = $this->getDataFromHandle($handles);

        if (count($handles) > 1) {
            return $result;
        }

        if (empty($result)) {
            return [];
        }

        return [
            $result
        ];
    }

    /**
     * Find Debtor by EAN
     *
     * @param  integer $value
     * @return array
     */
    protected function findByEan($value)
    {
        $handles = $this->client
            ->Debtor_FindByEan([
                'ean' => $value
            ])
            ->Debtor_FindByEanResult
            ->DebtorHandle;

        $result = $this->getDataFromHandle($handles);

        if (count($handles) > 1) {
            return $result;
        }

        if (empty($result)) {
            return [];
        }

        return [
            $result
        ];
    }

    /**
     * Find Debtor by email
     *
     * @param  string $value
     * @return array
     */
    protected function findByEmail($value)
    {
        $handles = $this->client
            ->Debtor_FindByEmail([
                'email' => $value
            ])
            ->Debtor_FindByEmailResult
            ->DebtorHandle;


        $result = $this->getDataFromHandle($handles);

        if (count($handles) > 1) {
            return $result;
        }

        if (empty($result)) {
            return [];
        }

        return [
            $result,
        ];
    }

    /**
     * Find Debtor by name
     *
     * @param $value
     * @return array|object
     */
    protected function findByName($value)
    {
        $handles = $this->client
            ->Debtor_FindByName([
                'name' => $value
            ])
            ->Debtor_FindByNameResult
            ->DebtorHandle;


        $result = $this->getDataFromHandle($handles);

        if (count($handles) > 1) {
            return $result;
        }

        if (empty($result)) {
            return [];
        }

        return [
            $result,
        ];
    }

    /**
     * Returns handles for debtors with a given partial name
     *
     * @param $value
     * @return array|object
     */
    public function findByPartialName($value)
    {
        $handles = $this->client
            ->Debtor_FindByPartialName([
                'partialName' => $value
            ])
            ->Debtor_FindByPartialNameResult
            ->DebtorHandle;


        $result = $this->getDataFromHandle($handles);

        if (count($handles) > 1) {
            return $result;
        }

        if (empty($result)) {
            return [];
        }

        return [
            $result,
        ];
    }

    /**
     * Update an existing Debtor
     *
     * @param  integer $no
     * @param  array   $data
     * @return object
     */
    public function updateField($no, $data)
    {
        $handle = $this->getHandle($no);

        foreach ($data as $field => $value) {
            $request = [
                'debtorHandle' => $handle,
                'value' => $value,
            ];

            switch (strtolower($field)) {
                case 'name':
                    $this->client
                        ->Debtor_SetName($request);
                    break;

                case 'vatzone':
                    $this->client
                        ->Debtor_SetVatZone($request);
                    break;

                case 'ean':
                    $this->client
                        ->Debtor_SetEan($request);
                    break;

                case 'email':
                    $this->client
                        ->Debtor_SetEmail($request);
                    break;

                case 'website':
                    $this->client
                        ->Debtor_SetWebsite($request);
                    break;

                case 'address':
                    $this->client
                        ->Debtor_SetAddress($request);
                    break;

                case 'postalcode':
                    $this->client
                        ->Debtor_SetPostalCode($request);
                    break;

                case 'city':
                    $this->client
                        ->Debtor_SetCity($request);
                    break;

                case 'country':
                    $this->client
                        ->Debtor_SetCountry($request);
                    break;

                case 'creditmaximum':
                    $this->client
                        ->Debtor_SetCreditMaximum($request);
                    break;

                case 'vatnumber':
                    $this->client
                        ->Debtor_SetVatNumber($request);
                    break;

                case 'county':
                    $this->client
                        ->Debtor_SetCounty($request);
                    break;

                case 'cinumber':
                    $this->client
                        ->Debtor_SetCINumber($request);
                    break;

                case 'group':
                    $group = new Group($this->client_raw);
                    $groupHandle = $group->getHandle($value);

                    $this->client
                        ->Debtor_SetDebtorGroup([
                            'debtorHandle' => $handle,
                            'valueHandle' => $groupHandle,
                        ]);
                    break;
            }
        }

        return $this->get($no);
    }

    /**
     * Create a new debtor
     *
     * @param  array  $data
     * @return object
     */
    public function create(array $data)
    {
        if (isset($data["number"])) {
            $number = $data["number"];
        }

        if (!isset($number)) {
            $number = $this->client
                ->Debtor_GetNextAvailableNumber()
                ->Debtor_GetNextAvailableNumberResult;
        }

        $groupHandle = [
            'Number' => 1
        ];

        if (isset($data['group'])) {
            $group = new Group($this->client_raw);
            $groupHandle = $group->getHandle($data['group']);
        }

        $debtor = $this->client
            ->Debtor_Create([
                'number'            => $number,
                'debtorGroupHandle' => $groupHandle,
                'name'              => $data['name'],
                'vatZone'           => $data['vatZone'],
            ])
            ->Debtor_CreateResult;

        $handle = $this->getHandle($number);

        $this->client
            ->Debtor_SetIsAccessible([
                'debtorHandle' => $handle,
                'value' => true,
            ]);

        return $this->update($number, $data);
    }


    /**
     * Update debtor (unfinished)
     *
     * @param $data
     * @return bool
     * @throws Exception
     */
    public function update($data)
    {
        $debitorHandle  = ['Number' => $data['number']];
        $groupHandle    = ['Number' => $data['group_number']];
        $currencyHandle = ['Code' => $data['currency_code']];
        $termHandle     = ['Name' => $data['payment_term_name']];
        $templateHandle = ['Name' => $data['template_name']];
        $contactHandle  = ['externalId' => $data['externalId']];

        $data = [
          'data' => (object) [
                'Handle' => $debitorHandle,
                'Number' => $data['number'],
                'VatZone' => $vat_zone,
                'IsAccessible' => true,
                'Name' => $params['company'],
                'Email' => $params['email'],
                'Address' => $params['address'],
                'PostalCode' => $params['postalcode'],
                'City' => $params['city'],
                'VatNumber' => $params['vat'],
                'Country' => $params['country'],
                'DebtorGroupHandle' => $groupHandle,
                'CurrencyHandle' =>  $currencyHandle,
                'TermOfPaymentHandle' => $termHandle,
                'LayoutHandle' => $templatecollectionHandle,
                'AttentionHandle' => $contact_handle
            ]
        ];

        try {
            $updatedDebitorHandle = $this->client
                ->Debtor_UpdateFromData($data)
                ->Debtor_UpdateFromDataResult;
        } catch (Exception $e) {
            throw new Exception('Could not update Debitor');
        }

        if (isset($contact_handle->Id)) {
            $contactData = [
                'data' => [
                    'Handle' => $contact_handle,
                    'Id' => $contact_handle->Id,
                    'ExternalId' => $params['external'],
                    'DebtorHandle' => $updatedDebitorHandle,
                    'Name' => $params['name'],
                    'Number' => $params['external'],
                    'Email' => $params['email'],
                    'IsToReceiveEmailCopyOfOrder' => false,
                    'IsToReceiveEmailCopyOfInvoice' => false
                ]
            ];

            try {
                $contact = $this->client->DebtorContact_UpdateFromData($contactData)->DebtorContact_UpdateFromDataResult;
            } catch (Exception $e) {
                throw new Exception('Could not update Debitor Contact information');
            };
        }

        if (!empty($params['debitor_ean'])) {
            $debtorEan = [
                'debtorHandle' => $debitor,
                'valueHandle' => $params['debitor_ean'],
            ];

            $this->client->Debtor_SetEan($debtorEan);
        }

        return true;
    }
}
