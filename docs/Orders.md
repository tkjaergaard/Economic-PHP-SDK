#### Getting started
The **Order Class** depends on getting a instance of the *Client Class* injected in order to function.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

#### Get all Orders
This method returns all Orders, including those which are archived.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    $all = $order->all();

#### Get all current Orders
This method only returns the Orders that are not archived and set as current.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    $current = $order->current();

#### Get a specific Order
This method returns a specific Order by the order number.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    $get = $order->get(10001);

#### Get Order Debtor
This method returns the Debtor of a specific Order by order number.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    $debtor = $order->debtor(10001);

#### Get or set sent status of a Order
This method either returns the sent status of a Order or lets you set the current sent status by setting the second paramater to `true` or `false`

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    // Returns current status
    $isSent = $order->sent(10001);

    // Set status to not sent
    $order->sent(10001, false);

    // Set status to sent
    $order->sent(10001, true);

#### Get a Order due date
This method return a specific Order due date.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    $due = $order->due(10001);

#### Get a Order total
This method lets you grap the total amount of a Order, either with or without VAT.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    // Without VAT
    $total = $order->total(10001);

    // With VAT
    $total = $order->total(10001, true);

#### Get if a Order is archived
This method returns `boolean` whether or not it is archived.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    $isArchived = $order->isArchived(10001);

#### Get the Lines of a Order
This method returns all Order lines of a specific Order.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    $lines = $order->lines(10001);

#### Get Order PDF
This method return the PDF of a order as a string.
If the second paramater is set to `true` force download will be invoked.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Order\Order;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $order = new Order($client);

    $pdf = $order->pdf(10001);

    // Invoke download
    $order->pdf(10001, true);

#### Create a new Order
This method lets you create a new Order to a specific Debtor.

The `add` method on the `line` object accepts a array containing information on the product line. The array accepts the following elements:

* **Product**: The product number. `required`
* **Description**: The line description. `optional`
* **Price**: The unit price of the line. `optional`
* **Qty**: The quantity of the line. `optional`
* **Unit**: The Unit number to use. `optional`

```
<?php
use tkj\Economics\TokenClient;
use tkj\Economics\Order\Order;

$client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
$order = new Order($client);

$debtorNumber = 101;

$newOrder = $order->create($debtorNumber, function($line)
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

#### Upgrade Order to Invoice
This method let's you upgrade a Order to a invoice and returns a object containing
the Invoice id for futher processing.

```
<?php
use tkj\Economics\TokenClient;
use tkj\Economics\Order\Order;

$client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
$order = new Order($client);

$orderNumber = 101;

$invoiceID = $order->upgrade($orderNumber);
```
