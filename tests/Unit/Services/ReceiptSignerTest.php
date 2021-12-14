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

        // TODO => Add values in order for test to work
        $primeSignBaseCertificateURL = 'rksv_primesign_base_certificate_url';
        $primeSignReceiptSignURL = 'rksv_primesign_receipt_sign_url';
        $primeSignTokenKey = 'rksv_primesign_token_key';
        $encryptionKey = 'AES_key';
        $primeSignCertificateNumber = 'RKSV_PRIMESIGN_CERTIFICATE_NUMBER';
        $tokenKey = 'rksv_primesign_token_key';
        $taxRates = ['20','10','13','0','special'];
        $locationId = 'rksv_primesign_location_id';

        $receiptSigner = new ReceiptSigner(
            $primeSignBaseCertificateURL,
            $primeSignReceiptSignURL,
            $primeSignTokenKey,
            $primeSignCertificateNumber,
            $encryptionKey,
            $tokenKey,
            $taxRates,
            $locationId,
            $receiptData
        );

        $receiptSigner->trainingSign();

        echo "\n\ntraining test signature: \n\n" . $receiptSigner->getSignature();
        echo "\n\ntraining test qr: \n\n" . $receiptSigner->getQR();

        $this->assertTrue(true);

    }

}