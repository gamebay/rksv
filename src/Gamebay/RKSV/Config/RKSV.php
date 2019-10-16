<?php

return [

    /**
     * base certificate url for remote signing
     */
    'rksv_primesign_base_certificate_url' => env('RKSV_PRIMESIGN_BASE_CERTIFICATE_URL', 'https://rs-fc8349ca.ps.prime-sign.com'),

    /** url for signing receipts */
    'rksv_primesign_receipt_sign_url' => env('RKSV_PRIMESIGN_RECEIPT_SIGN_URL', '/rs/rk/signatures/r1'),

    /**
     * Austrian tax number: Prefix of "Steuernummer " followed by 9 digits,
     * e.g. "CN=Steuernummer 123456789,O=Company name,C=AT"
     */
    'rksv_austrian_tax_number' => env('RKSV_AUSTRIAN_TAX_NUMBER', 'CN=Steuernummer 123456789,O=Company name,C=ATX'),

    /**
     * Token key for authenticating to PrimeSign
     */
    'rksv_primesign_token_key' => env('RKSV_PRIMESIGN_TOKEN_KEY', 'XXXXX-XXXXX-XXXXX-XXXXX-XXXXX'),

    /**
     * Location ID given by the primesign service, ie. AT0, AT1, AT2, AT3, etc.
     */
    'rksv_primesign_location_id' => env('RKSV_PRIMESIGN_LOCATION_ID', 'AT3'),

];
