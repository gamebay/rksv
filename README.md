# Austrian cash register fiscalization


**Please be aware this repository is still under active development** if you plan to use it in a production environment.


In Austria the fiscalization process is called Registrierkassensicherheitsverordnung (RKSV).


In this repository, we use about PrimeSign online sign feature (via Cryptas).

What we took from them is a simple service which accepts RKSV payloads and then gives you a properly encrypted & signed response.
Worry free and fewer complications with signing receipts.
So what you need for this package to work is the following:
- PHP
- [PrimeSign remote signing SaaS](https://www.cryptoshop.com/products/zertifikate.html) service for RKSV (for example: we took this [one](http://www.cryptoshop.com/products/zertifikate/rksv-primesign-remotesigning-hosted-bdl-24-7-150.html))

## How it works

First you have to create a receipt with data which will be used for signing:
```php
$receiptData = ReceiptData::withData(
    $receipt->cashBoxId->id, // Cash register ID
    $receipt->cashBoxId->daily_income, // Daily register income sum
    $receipt->number, // Receipt number
    \DateTime::createFromFormat('Y-m-d H:i:s', $receipt->created_at),
    [
        [
            'brutto' => 123,
            'tax' => 20        
        ],
        [
            'brutto' => 224,
            'tax' => 20 
        ]
    ],
    $receipt->previous_receipt->signature
);
```


Using the created `ReceiptData`, pass it on to `ReceiptSigner` with other PrimeSign arguments and call the desired method for signing:
```php

$primeSignBaseCertificateURL = 'Insert Prime Sign base certificate URL';
$primeSignReceiptSignURL = 'Insert Prime Sign receipt sign URL';
$primeSignTokenKey = 'Insert Prime Sign token key';
$encryptionKey = 'Insert Prime Sign encryption key';
$primeSignCertificateNumber = 'Insert Prime Sign certificate number';
$taxRates = ['20', '10', '13', '0', 'special'];
$locationId = 'Insert location ID';

$receiptSigner = new ReceiptSigner(
    $primeSignBaseCertificateURL,
    $primeSignReceiptSignURL,
    $primeSignTokenKey,
    $primeSignCertificateNumber,
    $encryptionKey,
    $taxRates,
    $locationId,
    $receiptData
);

if ($this->gross > 0) {
    $receiptSigner->normalSign();
}
```
Getting the result of signing:
```php
$signature = $receiptSigner->getSignature();
$QR = $receiptSigner->getQR();
```
Beside normal signer, you can also call:
 * cancel receipt signature `$receiptSigner->cancelSign();`
 * training receipt signature `$receiptSigner->trainingSign();`
    
Null sign will use the `cashBoxId` for generating chain value.
This is the first receipt you create when initializing receipt sequences.
The package will accept items, but will ignore them and overwrite with zero values.

```php
$receiptSigner->nullSign();
```

### Contributing

In case you notice any bugs, open up a new issue, so we can discuss it and fix it ASAP.

Keep in mind, this is an open source project and anyone can contribute by opening up a pull request so feel free to fix the bugs your selves in order to have them merged sooner.
If you want to add a feature, please open up an issue, and then we can discuss further actions. Without opening a new issue we will ignore any pull requests for new features.

With new features please describe the changes you made within the pull request and place a link to an open issue which relates to your pull request.

### Licence

The project is open sourced under GNU v3.0 public licence.


### TODO

- implement proper unit tests which should cover hashing, encryption, qr code generation and other major-impact topics
- implement proper unit tests for entire process - normal sign, training sign and cancel sign + chaining of all those
- implement command which will gneerate the null reciept that can also be used
- documentation on code and RKSV process
- complete remove sign service factory and implement all logic inside a SignService class.
- revise all exeptions and poperly test them via unit tests
- revise all validators and see on what are needed and which can be added
- expand on info on how to incorporate into other peojects (if needed, discuss)


### About us

[Gamebay](https://gamebay.io) is a platform for managing gaming arenas, providing full support for running games and offering cash register solution as all in one software.

We are partners with [Friendly Fire Esports](https://friendlyfireesports.com/en) arenas.
