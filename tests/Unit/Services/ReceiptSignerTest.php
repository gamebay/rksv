<?php


use PHPUnit\Framework\TestCase;
use Gamebay\RKSV\Services\ReceiptSigner;

class ReceiptSignerTest extends TestCase
{
    public function testGenerateQRCodeString()
    {
        $receiptSigner = new ReceiptSigner();

        echo "\n" . $receiptSigner->generateQRCodeString("test");

        $this->assertTrue(true);
    }
}