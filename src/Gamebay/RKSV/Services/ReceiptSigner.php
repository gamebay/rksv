<?php


namespace Gamebay\RKSV\Services;

use chillerlan\QRCode\QRCode;
use Gamebay\RKSV\Factory\SignServiceFactory;
use Gamebay\RKSV\ErrorHandlers\Exceptions\NoReceiptDataException;
use Illuminate\Http\Response;
use Gamebay\RKSV\Models\ReceiptData;

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
     * @return SignServices\SingServiceInterface
     * @throws \Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException
     */
    public function getSignService(string $sign_type)
    {
        return SignServiceFactory::create($sign_type);
    }

    /**
     * Generate QR code png image from string, using simple-qrcode package
     * Convert this image to base64 string representation ready to be used as src of an img tag
     * @param string $signature
     * @return string
     */
    public function generateQRCodeString(string $string)
    {
        return (new QRCode())->render($string);
    }

    /**
     * Signs the receiptData with appropriate signer, generates signature and QR code.
     * @param ReceiptData $receiptData
     * @param string $signType
     * @throws NoReceiptDataException
     */
    private function sign(ReceiptData $receiptData, string $signType)
    {
        $signatureType = self::NORMAL_SIGN_TYPE;

        $receiptData = $this->isReceiptDataSet($receiptData);

        $signInterface = $this->getSignService($signatureType);

        $this->signature = $signInterface->sign($receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    /**
     * Helper for normal sign.
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function normalSign(ReceiptData $receiptData = null)
    {
        $this->sign($receiptData, self::NORMAL_SIGN_TYPE);
    }

    /**
     * Helper for cancel/storno sign.
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function cancelSign(ReceiptData $receiptData = null)
    {
        $this->sign($receiptData, self::CANCEL_SIGN_TYPE);
    }

    /**
     * Helper for training sign.
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function trainingSign(ReceiptData $receiptData = null)
    {
        $this->sign($receiptData, self::TRAINING_SIGN_TYPE);
    }

    /**
     * Helper for null/first sign.
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function nullSign(ReceiptData $receiptData = null)
    {
        $this->sign($receiptData, self::NULL_SIGN_TYPE);
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