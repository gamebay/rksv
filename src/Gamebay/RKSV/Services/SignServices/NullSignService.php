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

    // $salesCounterCode = SignatureType::SIGN_CODE[ReceiptSigner::NULL_SIGN_TYPE]; // should return 0

    // $nullItem = [
    //   [
    //     'brutto' => 0,
    //     'tax' => 0,
    //   ]
    // ];

    $this->receiptData = $receiptData;

    // $this->receiptData->setItems($nullItem);
    $this->receiptData->setSalesCounter(0);  // should be 0 for zero reciept, same as SignatureType::SIGN_CODE[ReceiptSigner::NULL_SIGN_TYPE]
    $this->receiptData->setPreviousReceiptSignature($this->receiptData->getCashboxId()); // signature here should be cahbox id as it is zero reciept
  }
}

