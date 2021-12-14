<?php

namespace Gamebay\RKSV\Providers;

/**
 * Class PrimeSignProvider
 * @package Gamebay\Rksv\Providers
 */
class PrimeSignProvider
{
    /** @var string $baseUrl */
    public string $baseUrl;

    /** @var string $signUrl */
    public string $signUrl;

    /** @var string $fullSignatureUrl */
    public string $fullSignerUrl;

    /** @var string $apiTokenKey */
    public string $apiTokenKey;

    public function __construct(
        string $primeSignBaseCertificateURL,
        string $primeSignReceiptSignURL,
        string $primeSignTokenKey
    )
    {
        $this->baseUrl = $primeSignBaseCertificateURL;
        $this->signUrl = $primeSignReceiptSignURL;
        $this->fullSignerUrl = $this->baseUrl . $this->signUrl;
        $this->apiTokenKey = $primeSignTokenKey;
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