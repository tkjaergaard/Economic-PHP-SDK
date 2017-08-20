#### Getting started
The **Invoice Class** depends on getting a instance of the *Client Class* injected in order to function.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Invoice\Invoice;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $invoice = new Invoice($client);

#### Get all Invoices
This method returns all Invoices, including those who are archived.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Invoice\Invoice;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $invoice = new Invoice($client);

    $all = $invoice->all();

#### Get a specific Invoice by Invoice number
This method lets you grap a single invoice by it's number.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Invoice\Invoice;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $invoice = new Invoice($client);

    $get = $invoice->get(10001);

#### Get Invoice Due date by Invoice number.
This methods returns the Invoice due date.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Invoice\Invoice;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $invoice = new Invoice($client);

    $due = $invoice->due(10001);

#### Get a Invoice total
This method lets you grap the Invoice total without VAT by the invoice number.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Invoice\Invoice;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $invoice = new Invoice($client);

    // Without VAT
    $total = $invoice->total(10001);

    // With VAT
    $total = $invoice->total(10001, true);

#### Get Invoice VAT amount
This method return the VAT amount as a `float` for a specific Invoice.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Invoice\Invoice;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $invoice = new Invoice($client);

    $vat = $invoice->vat(10001);

#### Get Invoice Lines
This method lets you return a Invoice lines by the invoice number.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Invoice\Invoice;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $invoice = new Invoice($client);

    $lines = $invoice->lines(10001);

#### Get Invoice PDF
This method return a PDF as a string.

You can optionally set the second paramater to `true` to invoke download.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Invoice\Invoice;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $invoice = new Invoice($client);

    // Return PDF
    $pdf = $invoice->pdf(10001);

    // Invoke download
    $pdf = $invoice->pdf(10001, true);

#### Create a new Invoice
This method lets you create a new Invoice to a specific Debtor.

The `add` method on the `line` object accepts a array containing information on the product line. The array accepts the following elements:

* **Product**: The product number. `required`
* **Description**: The line description. `optional`
* **Price**: The unit price of the line. `optional`
* **Qty**: The quantity of the line. `optional`
* **Unit**: The Unit number to use. `optional`

```
<?php
use tkj\Economics\TokenClient;
use tkj\Economics\Invoice\Invoice;

$client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
$invoice = new Invoice($client);

$debtorNumber = 101;

$newInvoice = $invoice->create($debtorNumber, function($line)
{
    $data = array(
        "product"     => 301,
        "description" => "Description of line.",
        "price"       => 825.00,
        "qty"         => 5,
        "unit"        => 2
    );

    $line->add($data);
},$options=[]);
```

#### Book a Invoice
This method let's you book a invoice by Invoice number or ID handle and
returns an object containing the Book number.

	<?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Invoice\Invoice;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $invoice = new Invoice($client);

    // Return PDF
    $number = $invoice->book(10001);