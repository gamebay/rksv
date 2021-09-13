<?php

namespace Gamebay\RKSV\Services\SignServices;

use Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidItemException;
use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Providers\PrimeSignProvider;
use Gamebay\RKSV\Services\ReceiptSigner;
use Gamebay\RKSV\Validators\SignatureType;

/**
 * Class NullSignService
 * @package Gamebay\RKSV\Services\SignServices
 */
class NullSignService extends BaseSignService implements SignServiceInterface
{
    /**
     * NullSignService constructor.
     * @param PrimeSignProvider $provider
     * @param ReceiptData $receiptData
     * @param string $encryptionKey
     * @param string $tokenKey
     * @param array $taxRates
     * @param string $locationId
     * @throws InvalidItemException
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

        $salesCounterCode = SignatureType::SIGN_CODE[ReceiptSigner::NULL_SIGN_TYPE];

        $nullItem = [
            'brutto' => 0,
            'tax' => 20,
        ];

        $this->receiptData = $receiptData;

        $this->receiptData->setItems($nullItem);
        $this->receiptData->setSalesCounter($salesCounterCode);
        $this->receiptData->setPreviousReceiptSignature($this->receiptData->getCashboxId());
    }
}