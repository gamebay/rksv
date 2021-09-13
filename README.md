# Austrian cash register fiscalization
In austria the fiscalization proccess is called Registrierkassensicherheitsverordnung (RKSV).
As we at Gamebay found out, it is rather difficult to follow the process of fiscalization when almost everything is in German, and you don't really speak it that well.

Despite that we found a few repositories which made similar implementation BUT either weren't maintained anymore OR were not for PHP.
We had to do it our selves but then decided it would be of greater purpose (and probably less bugs) if we open source it and let everyone know how the process goes.

Since we wanted to be as quick as possible and use as little tools possible, we found about PrimeSign online sign feature.

What we took from them is a simple service which accepts RKSV payloads and then gives you a properly encrypted & signed response.
Worry free and less complications with signing receipts.
So what you need for this package to work is the following:
- PHP/[Laravel](https://laravel.com)
- [PrimeSign remote signing SaaS](https://www.cryptoshop.com/products/zertifikate.html) service for RKSV
- for example: we took this [one](http://www.cryptoshop.com/products/zertifikate/rksv-primesign-remotesigning-hosted-bdl-24-7-150.html)

Everything else you need for setting up the project is pretty much in the config file (RKSV.php)

## How it works

First you have to create a receipt with data which will be used for signing

    $receiptData = ReceiptData::withData(
                $receipt->cashBoxId->id, //cash register id
                $receipt->cashBoxId->daily_income, //daily register income sum
                $receipt->number, //receipt number
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

Using the created ReceiptData, pass it on to ReceiptSigner and call desired method for signing


            $receiptSigner = new ReceiptSigner($receiptData);
                if ($this->gross > 0) $receiptSigner->normalSign();

Getting the result of signing

            $signature = $receiptSigner->getSignature();
            $QR = $receiptSigner->getQR();

Beside normal signer, you can also call
 * cancel receipt signature
 * training receipt signature

    ...
    $receiptSigner->cancelSign();
    ...
    $receiptSigner->trainingSign();
    ...
    
Null sign will use the cashBoxId ID for generating chain value.
This is the first receipt you create when initializing receipt sequences.
The package will accept items, but will ignore them and overwrite with zero values

    ...
    $receiptSigner->nullSign();
    ...


### Contributing

In case you notice any bugs, open up a new issue so we can discuss it and fix it asap.

Keep in mind, this is an open source project and anyone can contribute by opening up a pull reqeust so feel free to fix the bugs your selves in order to have them merged sooner.
If you want to add a feature, please open up an issue and then we can discuss further actions. Without opening a new issue we will ignore any pull requests for new features.

With new features please describe the changes you made within the pull request and place a link to an open issue which relates to your pull request.

### Liscence

The project is open sourced under GNU v3.0 public liscence.


### About us

[Gamebay](https://gamebay.io) is a platform for managing gaming arenas, providing full support for running games and offering cash register solution as all in one software.

We are partners with [Friendly Fire Esports](https://friendlyfireesports.com/en) arenas.
