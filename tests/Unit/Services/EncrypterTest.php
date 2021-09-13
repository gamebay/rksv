<?php

use Gamebay\RKSV\Services\Encrypter;
use Gamebay\RKSV\Models\ReceiptData;
use PHPUnit\Framework\TestCase;

abstract class EncrypterTest extends TestCase
{
    public function testEncryptSalesCounter()
    {
        $salesCounter = '12345';

        $receiptData = new ReceiptData();
        $receiptData->setSalesCounter('100');
        $receiptData->setCashboxId('1');
        $receiptData->setReceiptId('123');
        
        echo "\n\n" . Encrypter::encryptSalesCounter($receiptData);
    }
}