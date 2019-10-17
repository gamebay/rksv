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
     * @param ReceiptData $receiptData
     * @return string
     * return will be given in base64 format of the encrypted string,
     * but only the first 8 characters are taken into base64 conversion
     */
    public static function encryptSalesCounter(ReceiptData $receiptData)
    {
        $algorithm = 'aes-256-ctr';
        $key = config('AES-key');
        $cashboxId = $receiptData->getCashboxId();
        $receiptId = $receiptData->getReceiptId();
        $salesCounter = $receiptData->getSalesCounter();

        $option = OPENSSL_NO_PADDING;

        /**
         * @var string $iv
         * initialization vector which is taken for AES-256-CTR encryption
         * the encryption method expects blocks of 16 bytes, so first 16 chars are taken from IV hash value
         */
        $iv = hash('sha256', $cashboxId . $receiptId);
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
         * @var array $restByteZeroArray
         * initialization of 8 byte array filled with zeros which is needed for the encryption
         */
        $restByteZeroArray = array_fill(0, 8, 0);

        /**
         * creating a 16 byte array which is needed for the encryption (which expects blocks of 16 bytes)
         */
        $codedSalesCounterArray = array_merge($salesCounterBigEndianByteArray, $restByteZeroArray);

        /** @var string $codedSalesCounterString */
        $codedSalesCounterString = implode(array_map('chr', $codedSalesCounterArray));

        /**
         * @var string $encryptedSalesCounter
         * finally encryption is done with the above prepared string
         */
        $encryptedSalesCounter = openssl_encrypt($codedSalesCounterString, $algorithm, $key, $option, $iv);

        return base64_encode(substr($encryptedSalesCounter, 0, 8));
    }
}