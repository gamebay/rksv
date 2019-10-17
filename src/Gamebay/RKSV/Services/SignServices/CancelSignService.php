<?php


namespace Gamebay\RKSV\Services\SignServices;


use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Providers\PrimeSignProvider;
use Gamebay\RKSV\Services\ReceiptSigner;
use GuzzleHttp\Psr7\Request;
use Gamebay\RKSV\Validators\SignatureType;

/**
 * Class CancelSignService
 * @package Gamebay\RKSV\Services\SignServices
 */
class CancelSignService extends BaseSignService implements SignServiceInterface
{

    /**
     * CancelSignService constructor.
     * @param PrimeSignProvider $provider
     * @param ReceiptData $receiptData
     * @param string|null $tokenKey
     * @param string|null $taxRates
     * @param string|null $locationId
     */
    public function __construct(PrimeSignProvider $provider, ReceiptData $receiptData, string $tokenKey = null, string $taxRates = null, string $locationId = null)
    {
        $salesCounterCode = SignatureType::SIGN_CODE[ReceiptSigner::CANCEL_SIGN_TYPE];

        $this->receiptData = $receiptData;
        $this->receiptData->setSalesCounter($salesCounterCode);

        parent::__construct($provider, $receiptData, $tokenKey, $taxRates, $locationId);
    }
    
}