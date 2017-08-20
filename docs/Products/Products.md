#### Getting started
The **Product Class** depends on getting a instance of the *Client Class* injected in order to function.

    <?php
    use Tkj\Economics\TokenClient;
    use tkj\Economics\Product\Product;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $product = new Product($client);

#### Get all Products
This method returns all products.

    <?php
    use Tkj\Economics\TokenClient;
    use tkj\Economics\Product\Product;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $product = new Product($client);

    $all = $product->all();

#### Get a specific Product
This method returns a specific Product by the product number.

    <?php
    use Tkj\Economics\TokenClient;
    use tkj\Economics\Product\Product;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $product = new Product($client);

    $get = $product->get(301);

#### Find a product by it's name
This method lets you retrive a product by it's excact name.

    <?php
    use Tkj\Economics\TokenClient;
    use tkj\Economics\Product\Product;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $product = new Product($client);

    $get = $product->find("my product");

#### Get all accessible Products
This method returns all accessible Products.

    <?php
    use Tkj\Economics\TokenClient;
    use tkj\Economics\Product\Product;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $product = new Product($client);

    $accessible = $product->accessible();

#### Get Product stock
This method returns the current stock of a specific Product by product number.

    <?php
    use Tkj\Economics\TokenClient;
    use tkj\Economics\Product\Product;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $product = new Product($client);

    $stock = $product->stock(301);

#### Create a new Product
This method lets you create a new product. The method accepts an array containing relevant data for the product.

The `$data` array accepts the following elements:

* `name` The name of the product **required**
* `group` The number of the group to place the product **required**
* `description` The product description
* `rrp` The recommended retail price
* `price` The product price
* `unit` The number of the unit to use

```
<?php
use Tkj\Economics\TokenClient;
use tkj\Economics\Product\Product;

$client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
$product = new Product($client);

$data = array(
    "name"        => "Time pris",
    "description" => "Fast time pris",
    "group"       => 3,
    "rrp"         => 700.00,
    "price"       => 825.00,
    "unit"        => 2
);

$newProduct = $product->create($data);
```
