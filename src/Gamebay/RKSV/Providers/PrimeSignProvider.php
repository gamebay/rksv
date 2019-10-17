<?php


namespace Gamebay\RKSV\Providers;


use GuzzleHttp\Client;

/**
 * Class PrimeSignProvider
 * @package Gamebay\Rksv\Providers
 */
class PrimeSignProvider
{
    /** @var string $baseUrl */
    public $baseUrl;

    /** @var string $signUrl */
    public $signUrl;

    /** @var string $fullSignatureUrl */
    public $fullSignerUrl;

    /** @var string $apiTokenKey */
    public $apiTokenKey;

    public function __construct()
    {
        $this->baseUrl = config('RKSV.rksv_primesign_base_certificate_url');
        $this->signUrl = config('RKSV.rksv_primesign_receipt_sign_url');
        $this->fullSignerUrl = $this->baseUrl . $this->signUrl;

        $this->apiTokenKey = config('RKSV.rksv_primesign_token_key');

    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getSignUri(): string
    {
        return $this->signUrl;
    }

    /**
     * @return string
     */
    public function getApiTokenKey(): string
    {
        return $this->apiTokenKey;
    }
}