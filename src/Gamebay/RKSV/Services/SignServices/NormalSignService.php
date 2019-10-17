<?php


namespace Gamebay\RKSV\Services\SignServices;


use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Providers\PrimeSignProvider;
use Gamebay\RKSV\Services\ReceiptSigner;
use Gamebay\RKSV\Validators\SignatureType;

/**
 * Class NormalSignService
 * @package Gamebay\RKSV\Services\SignServices
 */
class NormalSignService extends BaseSignService implements SignServiceInterface
{

    /**
     * NormalSignService constructor.
     * @param PrimeSignProvider $provider
     * @param ReceiptData $receiptData
     * @param string|null $tokenKey
     * @param string|null $taxRates
     * @param string|null $locationId
     */
    public function __construct(PrimeSignProvider $provider, ReceiptData $receiptData, string $tokenKey = null, string $taxRates = null, string $locationId = null)
    {
        parent::__construct($provider, $receiptData, $tokenKey, $taxRates, $locationId);
    }

}