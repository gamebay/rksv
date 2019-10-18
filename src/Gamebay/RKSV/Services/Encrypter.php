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
    private $encryptionKey;

    /**
     * Encrypter constructor.
     * @param string|null $encryptionKey
     */
    public function __construct(string $encryptionKey = null)
    {
        isset($encryptionKey) ? $this->encryptionKey = $encryptionKey : $this->encryptionKey = config('RKSV.AES_key');
    }

    /**
     * @param ReceiptData $receiptData
     * @param string|null $encryptionKey
     * @return string
     * return will be given in base64 format of the encrypted string,
     * but only the first 8 characters are taken into base64 conversion
     */
    public function encryptSalesCounter(ReceiptData $receiptData)
    {
        $algorithm = 'aes-256-ctr';

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
    public function base64url_encode(string $data)
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