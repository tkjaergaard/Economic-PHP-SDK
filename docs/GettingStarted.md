#### Getting started

This document briefly goes through the process of installing and setting up this package.

##### Installation

This package available through [packagist](https://packagist.org/packages/tkj/economics):

```bash
$ composer require tkj/economics:~2.0
```

##### Manually

Clone this repository and load the files manually or setup a `spl_autoload`.

##### Connection to Economic

Get your access/tokens from here: https://www.e-conomic.com/developer

###### Token authentication

```
<?php
use Tkj\Economics\TokenClient;

$client = new TokenClient($token, $appToken, $appIdentifier, $options=[]);
```

###### Options
The options parameter is passed directly to the [SoapClient](http://php.net/manual/en/soapclient.soapclient.php).
