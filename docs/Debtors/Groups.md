#### Getting started
The **Debtor Group Class** depends on getting a instance of the *Client Class* injected in order to function.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Group;

    $client = new Client($agreementNumber, $userID, $password);
    $group = new Group($client);

#### Get all Debtor Groups
This method returns an array of Debtor Groups.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Group;

    $client = new Client($agreementNumber, $userID, $password);
    $group = new Group($client);

    $all = $group->all();

#### Get a specific Debtor Group
This method returns a specific Debtor Group by the group numnber.

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Group;

    $client = new Client($agreementNumber, $userID, $password);
    $group = new Group($client);

    $get = $group->get(1);

#### Get all Debtors in a specific Debtor Group
This method returns an array of Debtors in a specific debtor group

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Group;

    $client = new Client($agreementNumber, $userID, $password);
    $group = new Group($client);

    $debtors = $group->debtors(1);

#### Create a new Debtor Group
This method lets you create a new Debtor Group.
The method returns the newly create Debtor Group as an array.

**Arguments:**
* name `The name of the Debtor Group`
* account `The account number`

    <?php
    use tkj\Economics\Client;
    use tkj\Economics\Debtor\Group;

    $client = new Client($agreementNumber, $userID, $password);
    $group = new Group($client);

    $newGroup = $group->create("Foreign", 5600);