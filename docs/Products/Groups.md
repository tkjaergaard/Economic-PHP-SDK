#### Getting started
The **Product Group Class** depends on getting a instance of the *Client Class* injected in order to function.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Product\Group;

    $client = new Client($agreementNumber, $userID, $password);
    $group  = new Group($client);

#### Get all Product Groups
This method returns all Product Groups.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Product\Group;

    $client = new Client($agreementNumber, $userID, $password);
    $group  = new Group($client);

    $all = $group->all();

#### Get specific Product Group
This method lets you grap a specific Product Group by the group number.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Product\Group;

    $client = new Client($agreementNumber, $userID, $password);
    $group  = new Group($client);

    $get = $group->get(1);

#### Get all Products in Group
This method lets you grap all Products within a specific group.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Product\Group;

    $client = new Client($agreementNumber, $userID, $password);
    $group  = new Group($client);

    $products = $group->products(1);