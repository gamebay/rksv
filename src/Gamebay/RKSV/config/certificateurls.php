<?php

return [

    /**
     * base certificate url for remote signing
     */
    'base_certificate_url' => env('BASE_CERTIFICATE_URL', 'https://<host>/rs/admin'),

    /**
     * Base url route for managing users
     */
    'users_url' => env('USER_CERTIFICATE_URL', '/users'),

    /**
     * Austrian tax number: Prefix of "Steuernummer " followed by 9 digits,
     * e.g. "CN=Steuernummer 123456789,O=Company name,C=AT"
     */
    'at_tax_number' => env('AUSTRIAN_TAX_NUMBER', 'CN=Steuernummer 123456789,O=Company name,C=AT'),
    'token_key' => env('PRIMESIGN_TOKEN_KEY', 'XXXXX-XXXXX-XXXXX-XXXXX-XXXXX'),

];
