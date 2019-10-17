<?php


use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Services\Encrypter;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Gamebay\RKSV\Services\ReceiptSigner;

class ReceiptSignerTest extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        // TODO: Implement createApplication() method.
    }

    public function testGenerateQRCodeString()
    {
        $receiptSigner = new ReceiptSigner();

        echo "\n" . $receiptSigner->generateQRCodeString("test");

        $this->assertTrue(true);
    }

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