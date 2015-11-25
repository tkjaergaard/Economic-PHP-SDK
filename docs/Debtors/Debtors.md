#### Getting started
The **Debtor Class** depends on getting a instance of the *Client Class* injected in order to function.

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);
```

#### Get all Debtors
This method returns all Debtors.

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $all = $debtor->all();
```

#### Get a specific Debtor
Returns a object for a specific Debtor.

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $debtorNumber = 1001;
    $get = $debtor->get($debtorNumber);
```

#### Search Debtor by field
This method Lets you search Debtors by a specific field.

Available fields to search:
* CI (CI Number)
* EAN (EAN Number)
* EMAIL
* NAME
* PARTIALNAME (Partial Name)
* NUMBER

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $restult = $debtor->search('foo@example.com', 'email');
```

#### Update existing Debtor
This method lets you update a existing Debtors data.

The method accepts 2 paramaters. The first is the `debtor number` and the second is an array containing the `data` you wants to update.

The available elements to set in the `data array` is:
Required `data array` elements:
* **name** - The Debtor company name
* **group** - The Debtor group number
* **vatZone** - The Debtor vat zone `HomeCountry|EU|Abroad`
* **Ean** - The Debtor EAN Number
* **Email** - The Debtor email address
* **Website** - The Debtor url
* **Address** - The Debtor address
* **PostalCode** - The Debtor zip code
* **City** - The Debtor city
* **Country** - The Debtor country
* **CreditMaximum** - The Debtor max credit
* **VatNumber** - The Debtor VAT number `Only SE and UK accounts`
* **County** - The Debtor county ( UK )
* **CINumber** - The Debtor CI number

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $data = array(
        'Ean'           => 0000123456789,
        'Email'         => 'foo@example.com',
        'Website'       => 'http://example.com',
        'Address'       => 'Some Ally 123',
        'PostalCode'    => 2000,
        'City'          => 'Copenhagen',
        'CreditMaximum' => 30000.00,
        'VatNumber'     => 12345678,
        'CINumber'      => 12345678
    );

    $debtor->update($debtorNumber, $data);
```

#### Get all Debtor Quotations
This method lets you retrive a object containing all Quotaions for a specific Debtor.

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $quotations = $debtor->quotations($debtorNumber);
```

#### Get all Debtor Orders
This method lets you retrive all Orders for a specific Debor.

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $orders = $debtor->orders($debtorNumber);
```

#### Get all Debtor Invoices
This method lets you retrive all Invoices for a specific Debtor.

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $invoies = $debtor->invoices($debtorNumber);
```

#### Get all Debtor Contacts
This method lets you retrive all Contacts for a specific Debtor.

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $contacts = $debtor->contacts($debtorNumber);
```

#### Get the Balance for Debtor
This method lets you retrive the Balance for a specific Debtor.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $balance = $debtor->balance($debtorNumber);

#### Get the Address for Debtor
This method lets you retrive the Address for a specific Debtor

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $address = $debtor->address($debtorNumber);
```

#### Create new Debtor
This method lets you create a new Debtor.

Required `data array` elements:
* **name** - The Debtor company name
* **group** - The Debtor group number
* **vatZone** - The Debtor vat zone `HomeCountry|EU|Abroad`

Optional `data array` elements:
* **Ean** - The Debtor EAN Number
* **Email** - The Debtor email address
* **Website** - The Debtor url
* **Address** - The Debtor address
* **PostalCode** - The Debtor zip code
* **City** - The Debtor city
* **Country** - The Debtor country
* **CreditMaximum** - The Debtor max credit
* **VatNumber** - The Debtor VAT number `Only SE and UK accounts`
* **County** - The Debtor county ( UK )
* **CINumber** - The Debtor CI number

```
    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Debtor;

    $client = new Client($agreementNumber, $userID, $password);
    $debtor = new Debtor($client);

    $data = array(
        "group"         => 1,
        "name"          => "Company ltd.",
        "vatZone"       => "HomeCountry",

        "Ean"           => 0000123456789,
        "Email"         => "info@company.com",
        "Website"       => "http://company.com",
        "Address"       => "Some Alley 123",
        "PostalCode"    => 2000,
        "Country"       => "Copenhagen",
        "CreditMaximum" => 30000.00,
        "VatNumber"     => 12345678,
        "CINumber"      => 12345678
    );

    $debtorNumber = $debtor->create($data);
```
