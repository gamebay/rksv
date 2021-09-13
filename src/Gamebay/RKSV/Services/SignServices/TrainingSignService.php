<?php

namespace Gamebay\RKSV\Services\SignServices;

use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Providers\PrimeSignProvider;
use Gamebay\RKSV\Services\ReceiptSigner;
use Gamebay\RKSV\Validators\SignatureType;

/**
 * Class TrainingSignService
 * @package Gamebay\RKSV\Services\SignServices
 */
class TrainingSignService extends BaseSignService implements SignServiceInterface
{
    /**
     * TrainingSignService constructor.
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
    )
    {
        parent::__construct($provider, $receiptData, $encryptionKey, $tokenKey, $taxRates, $locationId);

        $salesCounterCode = SignatureType::SIGN_CODE[ReceiptSigner::TRAINING_SIGN_TYPE];

        $this->receiptData = $receiptData;
        $this->receiptData->setSalesCounter($salesCounterCode);
    }
}