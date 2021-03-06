<?php


namespace Gamebay\RKSV\Services\SignServices;


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
     * @param string|null $tokenKey
     * @param string|null $taxRates
     * @param string|null $locationId
     */
    public function __construct(PrimeSignProvider $provider, ReceiptData $receiptData, string $tokenKey = null, string $taxRates = null, string $locationId = null)
    {
        parent::__construct($provider, $receiptData, $tokenKey, $taxRates, $locationId);

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