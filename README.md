# Austrian cash register fiscalization
In austria the fiscalization proccess is called Registrierkassensicherheitsverordnung (RKSV).
As we at Gamebay found out, it is rather difficult to follow the process of fiscalization when almost everything is in German, and you don't really speak it that well.

Despite that we found a few repositories which made similar implementation BUT either weren't maintained anymore OR were not for PHP.
We had to do it our selves but then decided it would be of greater purpose (and probably less bugs) if we open source it and let everyone know how the process goes.

Since we wanted to be as quick as possible and use as little tools possible, we found about PrimeSign online sign feature.

What we took from them is a simple service which accepts RKSV payloads and then gives you a properly encrypted & signed response.
Worry free and less complications with signing receipts.
So what you need for this package to work is the following:
- PHP/Laravel
- PrimeSign remote signing SaaS service for RKSV (https://www.cryptoshop.com/products/zertifikate.html)
- for example: we took this one (http://www.cryptoshop.com/products/zertifikate/rksv-primesign-remotesigning-hosted-bdl-24-7-150.html)

Everything else you need for setting up the project is pretty much in the config file (RKSV.php)

## How it works

    $receiptData = ReceiptData::withData(
                $receipt->cashbox->id, //cash register id
                $receipt->cashbox->daily_income, //daily register income sum
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
                AtFiscalization::latest('created_at')->first()->signature ?? ''
            );
            $receiptSigner = new ReceiptSigner($receiptData);
            if ($nullSign == true) {
                $receiptSigner->nullSign();
            } else if ($training == true) {
                $receiptSigner->trainingSign();
            } else {
                if ($this->gross > 0) $receiptSigner->normalSign();
                if ($this->gross < 0) $receiptSigner->cancelSign();
            }
            $atFiscalization = new AtFiscalization();
            $atFiscalization->invoice_id = $this->id;
            $atFiscalization->signature = $receiptSigner->getSignature();
            $atFiscalization->qr = $receiptSigner->getQR();
            $atFiscalization->fiscal_at = Gamearena::currentTime();
            $atFiscalization->save();     
   