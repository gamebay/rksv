<?php

namespace Gamebay\RKSV\Services;

use Gamebay\RKSV\Models\ReceiptData;

/**
 * Class Encrypter
 * @package Gamebay\RKSV\Services
 */
class Encrypter
{
    /**
     * @var string $encryptionKey
     */
    private string $encryptionKey;

    /**
     * Encrypter constructor.
     *
     * @param string $encryptionKey
     */
    public function __construct(string $encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * @param ReceiptData $receiptData
     * @return string
     */
    public function encryptSalesCounter(ReceiptData $receiptData): string
    {
        $cashBoxId = $receiptData->getCashboxId();
        $receiptId = $receiptData->getReceiptId();
        $salesCounter = $receiptData->getSalesCounter();

        $bin = pack('J', intval($salesCounter * 100));
        $iv = substr(hash('sha256', $cashBoxId . $receiptId, true), 0, 16);    
        $encryptedValue = openssl_encrypt($bin, 'AES-256-CTR', $this->encryptionKey, 0, $iv);
    
        // Return the encrypted value directly, without additional processing (base64 encoded).
        return $encryptedValue;
    }

    /**
     * @param string $signature
     * @return bool|string
     */
    public function getCompactSignature(string $signature)
    {
        $hash = hash('sha256', $signature, true);
        $first8Bytes = substr($hash, 0, 8);
        $valueBase64 = base64_encode($first8Bytes);
        return $valueBase64;
    }

    /**
     * @param string $data
     * @return string
     */
    public function base64url_encode(string $data): string
    {
        return strtr(base64_encode($data), '+/', '-_');
    }

    /**
     * @param string $data
     * @return bool|string
     */
    public function base64url_decode(string $data)
    {
        return base64_decode(strtr($data, '-_', '+/'), false);
    }
}