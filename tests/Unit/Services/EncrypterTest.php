<?php


use PHPUnit\Framework\TestCase;
use Gamebay\RKSV\Services\Encrypter;
use Gamebay\RKSV\Models\ReceiptData;

class EncrypterTest extends TestCase
{
    public function testEncryptSalesCounter()
    {
        $salesCounter = '12345';

        $receiptData = new ReceiptData();
        $receiptData->setSalesCounter('100');
        $receiptData->setCashboxId('1');
        $receiptData->setReceiptId('123');

        echo "\n" . Encrypter::encryptSalesCounter($receiptData);
    }
}