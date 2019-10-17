<?php


namespace Gamebay\RKSV\Services;


use Gamebay\RKSV\Models\ReceiptData;

/**
 * Class Encrypter
 * @package Gamebay\RKSV\Services
 */
class Encrypter
{
    public static function encryptSalesCounter(ReceiptData $receiptData)
    {
        $algorithm = 'aes-256-ctr';
        $key = '123456'; //config('AES-key');
        $option = OPENSSL_NO_PADDING;

        $cashboxId = $receiptData->getCashboxId();
        $receiptId = $receiptData->getReceiptId();
        $salesCounter = $receiptData->getSalesCounter();

        $iv = hash('sha256', $cashboxId . $receiptId);
        $iv = substr($iv, 0, 16);

        $salesCounterBigEndianByteArray = unpack("C*", pack('E', floatval($salesCounter)));
        $bytesNumber = count($salesCounterBigEndianByteArray);
        assert($bytesNumber >= 5);
        $restByteZeroArray = array_fill(0, 16-$bytesNumber, 0);
        $codedSalesCounterArray = array_merge($salesCounterBigEndianByteArray, $restByteZeroArray);
        $codedSalesCounterString = implode(array_map('chr', $codedSalesCounterArray));
        assert(strlen($codedSalesCounterString) == 16);

        $encryptedSalesCounter = openssl_encrypt($codedSalesCounterString, $algorithm, $key, $option, $iv);

        return base64_encode(substr($encryptedSalesCounter, 0, 8));
    }
}