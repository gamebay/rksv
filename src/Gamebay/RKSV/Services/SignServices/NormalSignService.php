<?php

namespace Gamebay\RKSV\Services\SignServices;

use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Providers\PrimeSignProvider;

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
     * @param string $encryptionKey
     * @param string $tokenKey
     * @param array $taxRates
     * @param string $locationId
     */
    public function __construct(
        PrimeSignProvider $provider,
        ReceiptData $receiptData,
        string $encryptionKey,
        string $tokenKey,
        array $taxRates,
        string $locationId
    ) {
        parent::__construct($provider, $receiptData, $encryptionKey, $tokenKey, $taxRates, $locationId);
    }
}