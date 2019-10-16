<?php


use PHPUnit\Framework\TestCase;
use Gamebay\RKSV\Services\ReceiptSigner;

class ReceiptSignerTest extends TestCase
{
    public function testTestt()
    {
        $receiptSigner = new ReceiptSigner();

        echo "\n" . $receiptSigner->generateQRCodeString("test");

        $this->assertTrue(true);
    }
}