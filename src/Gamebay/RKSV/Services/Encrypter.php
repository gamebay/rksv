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
     * Return will be given in base64 format of the encrypted string,
     * but only the first 8 characters are taken into base64 conversion
     */
    public function encryptSalesCounter(ReceiptData $receiptData): string
    {
        $algorithm = 'aes-256-ctr';

        $cashBoxId = $receiptData->getCashboxId();
        $receiptId = $receiptData->getReceiptId();
        $salesCounter = $receiptData->getSalesCounter();

        $option = OPENSSL_NO_PADDING;

        /**
         * @var string $iv
         * Initialization vector which is taken for AES-256-CTR encryption
         * The encryption method expects blocks of 16 bytes, so first 16 chars are taken from IV hash value
         */
        $iv = hash('sha256', $cashBoxId . $receiptId);
        $iv = substr($iv, 0, 16);

        /**
         * https://www.php.net/manual/en/function.pack.php
         * pack - Pack data into binary string
         * J - unsigned long long (always 64 bit, big endian byte order)
         *
         * https://www.php.net/manual/en/function.unpack.php
         * unpack - unpack — Unpack data from binary string
         * C - unsigned char
         *
         * The resulting is array of 8 bytes
         */
        $salesCounterBigEndianByteArray = unpack("C*", pack('J', intval($salesCounter)));

        /**
         * Initialization of 8 byte array filled with zeros which is needed for the encryption
         */
        $restByteZeroArray = array_fill(0, 8, 0);

        /**
         * Creating a 16 byte array which is needed for the encryption (which expects blocks of 16 bytes)
         */
        $codedSalesCounterArray = array_merge($salesCounterBigEndianByteArray, $restByteZeroArray);

        $codedSalesCounterString = implode(array_map('chr', $codedSalesCounterArray));

        /**
         * @var string $encryptedSalesCounter
         * Finally encryption is done with the above prepared string
         */
        $encryptedSalesCounter = openssl_encrypt($codedSalesCounterString, $algorithm, $this->encryptionKey, $option, $iv);

        return base64_encode(substr($encryptedSalesCounter, 0, 8));
    }

    /**
     * @param string $signature
     * @return bool|string
     */
    public function getCompactSignature(string $signature)
    {
        return substr(hash('sha256', $signature), 0, 8);
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