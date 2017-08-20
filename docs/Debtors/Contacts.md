#### Getting started
The **Debtor Contact Class** depends on getting a instance of the *Client Class* injected in order to function.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Debtor\Contact;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $contact = new Contact($client);


#### Get all Contacts
This method returns all contacts as an array.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Debtor\Contact;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $contact = new Contact($client);

    $all = $contact->all();

#### Get Contact by ID
This method lets you grap the data of a Contact by the ID.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Debtor\Contact;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $contact = new Contact($client);

    $data = $contact->findById($id);

#### Search a contact by name
This method lets you serach a contact by their name.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Debtor\Contact;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $contact = new Contact($client);

    $results = $contact->search('John Doe');

#### Create a new Contact
This method lets you creata a new Contact.
The method returns an array with the details of the new contact.

**Arguments:**
* $data `array` `See the update method for more details`
* $debtor `integer` `The debtor number to assign the contact to`

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Debtor\Contact;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $contact = new Contact($client);

    $data = array(
        "name"    => "John Doe", // Required!
        "email"   => "johndoe@example.com",
        "phone"   => "12345678",
        "invoice" => true,
        "order"   => true,
        "comment" => "Some Comment to the contact"
    );

    $new = $contact->create($data, 101);

#### Update an existing contact.
This method lets you update an existing Contact.

**Arguments:**
* $data `array`
* $id `integer` `Id of the contact to update`

**The Data Array:**
* Name `string` `the name of the contact`
* Email `string` `The email of the contact`
* Phone `string` `The phone number of the contact`
* Invoice `boolean` `Should the contact recive a copy of invoices?`
* Order `boolean` `Should the contact recive a copy of orders?`
* Comment `string` `Add a comment to the contact`

** All of the above elements are optional. **

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Debtor\Contact;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $contact = new Contact($client);

    $data = array(
        "name"    => "John Doe",
        "email"   => "johndoe@example.com",
        "phone"   => "12345678",
        "invoice" => true,
        "order"   => true,
        "comment" => "Some Comment to the contact"
    );

    $new = $contact->update($data, 10);

#### Delete a contact
This method lets you delete a Contact by ID.

    <?php
    use tkj\Economics\TokenClient;
    use tkj\Economics\Debtor\Contact;

    $client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
    $contact = new Contact($client);

    $contact->delete($id);