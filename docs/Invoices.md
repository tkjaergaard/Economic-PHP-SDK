#### Getting started
The **Invoice Class** depends on getting a instance of the *Client Class* injected in order to function.

    <?php
    use devdk\Economics\Client;
    use devdk\Economics\Invoice\Invoice;

    $client = new Client($agreementNumber, $userID, $password);
    $invoice = new Invoice($client);

#### Get all Invoices
This method returns all Invoices, including those who are archived.

    <?php
    use devdk\Economics\Client;
    use devdk\Economics\Invoice\Invoice;

    $client = new Client($agreementNumber, $userID, $password);
    $invoice = new Invoice($client);

    $all = $invoice->all();

#### Get a specific Invoice by Invoice number
This method lets you grap a single invoice by it's number.

    <?php
    use devdk\Economics\Client;
    use devdk\Economics\Invoice\Invoice;

    $client = new Client($agreementNumber, $userID, $password);
    $invoice = new Invoice($client);

    $get = $invoice->get(10001);

#### Get Invoice Due date by Invoice number.
This methods returns the Invoice due date.

    <?php
    use devdk\Economics\Client;
    use devdk\Economics\Invoice\Invoice;

    $client = new Client($agreementNumber, $userID, $password);
    $invoice = new Invoice($client);

    $due = $invoice->due(10001);

#### Get a Invoice total
This method lets you grap the Invoice total without VAT by the invoice number.

    <?php
    use devdk\Economics\Client;
    use devdk\Economics\Invoice\Invoice;

    $client = new Client($agreementNumber, $userID, $password);
    $invoice = new Invoice($client);

    // Without VAT
    $total = $invoice->total(10001);

    // With VAT
    $total = $invoice->total(10001, true);

#### Get Invoice VAT amount
This method return the VAT amount as a `float` for a specific Invoice.

    <?php
    use devdk\Economics\Client;
    use devdk\Economics\Invoice\Invoice;

    $client = new Client($agreementNumber, $userID, $password);
    $invoice = new Invoice($client);

    $vat = $invoice->vat(10001);

#### Get Invoice Lines
This method lets you return a Invoice lines by the invoice number.

    <?php
    use devdk\Economics\Client;
    use devdk\Economics\Invoice\Invoice;

    $client = new Client($agreementNumber, $userID, $password);
    $invoice = new Invoice($client);

    $lines = $invoice->lines(10001);

#### Get Invoice PDF
This method return a PDF as a string.

You can optionally set the second paramater to `true` to invoke download.

    <?php
    use devdk\Economics\Client;
    use devdk\Economics\Invoice\Invoice;

    $client = new Client($agreementNumber, $userID, $password);
    $invoice = new Invoice($client);

    // Return PDF
    $pdf = $invoice->pdf(10001);

    // Invoke download
    $pdf = $invoice->pdf(10001, true);
    
    
#### Book a Invoice
This method let's you book a invoice by Invoice number or ID handle and
returns an object containing the Book number.

	<?php
    use devdk\Economics\Client;
    use devdk\Economics\Invoice\Invoice;

    $client = new Client($agreementNumber, $userID, $password);
    $invoice = new Invoice($client);

    // Return PDF
    $number = $invoice->book(10001);