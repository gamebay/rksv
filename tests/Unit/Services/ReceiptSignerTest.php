<?php


use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Services\Encrypter;
use Gamebay\RKSV\Services\ReceiptSigner;
use PHPUnit\Framework\TestCase;

class ReceiptSignerTest extends TestCase
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

//        echo "\n" . $receiptSigner->generateQRCodeString('12355', "test");

        $this->assertTrue(true);
    }

    public function testEncryptSalesCounter()
    {
        $salesCounter = '12345';

        $receiptData = new ReceiptData();
        $receiptData->setSalesCounter('100');
        $receiptData->setCashboxId('1');
        $receiptData->setReceiptId('123');

        $encrypter = new Encrypter('1234');

//        echo "\n\n" . $encrypter->encryptSalesCounter($receiptData);

        $this->assertTrue(true);
    }

    public function testTrainingSign()
    {
        $items = [
            ['brutto' => 12.59, 'tax' => 20],
            ['brutto' => 20.44, 'tax' => 20],
        ];

        $receiptData = ReceiptData::withData('1', '2000', '01', new DateTime('2019-10-17 14:52:20'), $items, '123123');

        $receiptSigner = new ReceiptSigner($receiptData);
        $receiptSigner->trainingSign();

        echo "\n\ntraining test signature: \n\n" . $receiptSigner->getSignature();
        echo "\n\ntraining test qr: \n\n" . $receiptSigner->getQR();

        $this->assertTrue(true);

    }

}