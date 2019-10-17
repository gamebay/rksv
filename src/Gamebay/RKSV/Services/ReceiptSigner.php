<?php


namespace Gamebay\RKSV\Services;

use chillerlan\QRCode\QRCode;
use Gamebay\RKSV\Factory\SignServiceFactory;
use Gamebay\RKSV\ErrorHandlers\Exceptions\NoReceiptDataException;
use Illuminate\Http\Response;
use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Validators\SignatureType;

/**
 * Class ReceiptSigner
 * @package Services
 */
class ReceiptSigner
{
    private $receiptData;
    private $signature;
    private $qr;

    const NORMAL_SIGN_TYPE = 'normal';
    const CANCEL_SIGN_TYPE = 'storno';
    const TRAINING_SIGN_TYPE = 'training';
    const NULL_SIGN_TYPE = 'null';

    /**
     * ReceiptSigner constructor.
     * @param ReceiptData $receiptData
     */
    public function __construct(ReceiptData $receiptData = null)
    {
        if ($receiptData != null) {
            $this->receiptData = $receiptData;
        }
    }

    /**
     * @param ReceiptData $receiptData
     */
    public function setReceiptData(ReceiptData $receiptData)
    {
        $this->receiptData = $receiptData;
    }

    /**
     * @return ReceiptData
     */
    public function getReceiptData()
    {
        return $this->receiptData;
    }

    /**
     * @return null|string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @return null|string
     */
    public function getQR()
    {
        return $this->qr;
    }

    /**
     * Get appropriate Sign Service
     * @param string $sign_type
     * @return SignServices\SignServiceInterface
     * @throws \Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException
     * @throws NoReceiptDataException
     */
    public function getSignService(string $sign_type)
    {
        $this->isReceiptDataSet($this->receiptData);

        $signatureType = new SignatureType($sign_type);
        $signServiceFactory = new SignServiceFactory($this->receiptData);

        return $signServiceFactory->create($signatureType);
    }

    /**
     * Generate QR code png image from string, using simple-qrcode package
     * Convert this image to base64 string representation ready to be used as src of an img tag
     * @param string $compactReceiptData
     * @param string $signature
     * @return string
     */
    public function generateQRCodeString(string $compactReceiptData, string $signature)
    {
        $encrypter = new Encrypter('AES-key-123');

        $signature = $encrypter->base64url_decode($signature);
        $signature = base64_encode($signature);

        return (new QRCode())->render($compactReceiptData . '_' . $signature);
    }

    /**
     * Signs the receiptData with appropriate signer, generates signature and QR code.
     * @param string $signType
     * @throws NoReceiptDataException
     * @throws \Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException
     */
    private function sign(string $signType)
    {
        $signInterface = $this->getSignService($signType);

        $compactReceiptData = $signInterface->generateCompactReceiptData();

        $response = $signInterface->sign($compactReceiptData);
        $this->signature = $response->getBody()->getContents();
        $this->qr = $this->generateQRCodeString($compactReceiptData, $this->signature);
    }

    /**
     * Helper for normal sign.
     * @throws NoReceiptDataException
     * @throws \Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException
     */
    public function normalSign()
    {
        $this->sign(self::NORMAL_SIGN_TYPE);
    }

    /**
     * Helper for cancel/storno sign.
     * @throws NoReceiptDataException
     * @throws \Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException
     */
    public function cancelSign()
    {
        $this->sign(self::CANCEL_SIGN_TYPE);
    }

    /**
     * Helper for training sign.
     * @throws NoReceiptDataException
     * @throws \Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException
     */
    public function trainingSign()
    {
        $this->sign(self::TRAINING_SIGN_TYPE);
    }

    /**
     * Helper for null/first sign.
     * @throws NoReceiptDataException
     * @throws \Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException
     */
    public function nullSign()
    {
        $this->sign(self::NULL_SIGN_TYPE);
    }

    /**
     * @param ReceiptData $receiptData
     * @return ReceiptData
     * @throws NoReceiptDataException
     */
    private function isReceiptDataSet(ReceiptData $receiptData = null): ReceiptData
    {
        $receiptData ?: $receiptData = $this->receiptData;

        if (null === $receiptData) {
            throw new NoReceiptDataException();
        }

        return $receiptData;
    }
}