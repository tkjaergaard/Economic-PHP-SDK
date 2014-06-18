#### Getting started
The **Quotation Class** depends on getting a instance of the *Client Class* injected in order to function.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

#### Get all Quotations
This method returns all Quotations.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

    $all = $quotation->all();

#### Get a specific Quotation
Returns a object for a specific Quotation.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

    $quotationNumber = 1001;
    $get = $quotation->get($quotationNumber);

#### Get a specific Quotation due date
This method returns a the due date for a given Quotation.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

    $quotationNumber = 1001;
    $due = $quotation->due($quotationNumber);

#### Get all Quotation lines
Get a object of all Quotation lines.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

    $quotationNumber = 1001;
    $lines = $quotation->lines($quotationNumber);

#### Get Quotation total net amount
Get the net amount of a Quotation as float

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

    $quotationNumber = 1001;
    $amount = $quotation->total($quotationNumber);

#### Get the PDF of a Quotation
This method return by default a base64 string of the pdf.
You can invoke download of the pdf by setting the secound paramater as `true`.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

    $quotationNumber = 1001;
    $pdf = $quotation->pdf($quotationNumber);

    // Force download
    $quotation->pdf($quotationNumber, true);

#### Get or set a Quotation sent satus
This method returns by default a boolean of the current sent status.
To change the sent status you can set the second paramater to a `boolean`.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

    $quotationNumber = 1001;
    $isSent = $quotation->sent($quotationNumber);

    // Set as sent
    $quotation->sent($quotationNumber, true);

    // Set as not sent
    $quotation->sent($quotationNumber, false);

#### Upgrade a Quotation to a order
This method upgrades a given Quotation to a order and returns the `order id`.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

    $quotationNumber = 1001;
    $orderID = $quotation->upgrade($quotationNumber);

#### Create a Quotation
This methods helps you to easily create a Quotation and returns a object containing Quotation details.

The `add` method on the `line` object accepts a array containing information on the product line. The array accepts the following elements:

* **Product**: The product number. `required`
* **Description**: The line description. `optional`
* **Price**: The unit price of the line. `optional`
* **Qty**: The quantity of the line. `optional`
* **Unit**: The Unit number to use. `optional`

```
<?php
use tkj\Economics\Client;
use tkj\Economics\Quotation\Quotation;

$client = new Client($agreementNumber, $userID, $password);
$quotation = new Quotation($client);

$new_quotation = $quotation->create($debtorNumber, function($line)
{
    $data = array(
        "product"     => 301,
        "description" => "Description of line.",
        "price"       => 825.00,
        "qty"         => 5,
        "unit"        => 2
    );
    $line->add($data);
});
```

#### Delete a specific Quotation
This method deletes a Quotation by it's number.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Quotation\Quotation;

    $client = new Client($agreementNumber, $userID, $password);
    $quotation = new Quotation($client);

    $quotationNumber = 1001;
    $quotation->delete($quotationNumber);