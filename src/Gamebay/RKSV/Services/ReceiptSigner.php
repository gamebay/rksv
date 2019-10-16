<?php


namespace Gamebay\RKSV\Services;

use Factory\SignServiceFactory;
use Gamebay\RKSV\ErrorHandlers\Exceptions\NoReceiptDataException;
use Gamebay\RKSV\Models\ReceiptData;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Class ReceiptSigner
 * @package Services
 */
class ReceiptSigner
{
    private $receiptData;
    private $signature;
    private $qr;
    private $tmpQRimage = '/tmp/tmp-qr.png';

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
        QrCode::format('png')->generate($string, $this->tmpQRimage);
        $data = file_get_contents($this->tmpQRimage);
        return 'data:image/png;base64,' . base64_encode($data);
    }

    /**
     * Signs the receiptData with appropriate signer, generates signature and QR code.
     * @param ReceiptData $receiptData
     * @param string $signType
     * @throws NoReceiptDataException
     */
    private function sign(ReceiptData $receiptData, string $signType)
    {
        if ($receiptData == null && $this->receiptData == null) {
            throw new NoReceiptDataException();
        }

        $signInterface = $this->getSignService($signType);

        $this->signature = $signInterface->sign($receiptData ?? $this->receiptData);
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
}