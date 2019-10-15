<?php


namespace Gamebay\RKSV\Services;

use Gamebay\RKSV\ErrorHandlers\Exceptions\NoReceiptDataException;
use Models\ReceiptData;
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
     * @return mixed
     */
    public function getSignService(string $sign_type)
    {
        return SignServiceFactory::create($sign_type);
    }

    /**
     * Generate QR code from signature, using simple-qrcode package
     * @param string $signature
     * @return string
     */
    public function generateQRCode(string $signature)
    {
        return QrCode::generate($signature);
    }

    /**
     * Sign normal receipt; obtain signature and QR code
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function normalSign(ReceiptData $receiptData = null)
    {
        if ($receiptData == null && $this->receiptData == null) {
            throw new NoReceiptDataException("use ReceiptSigner@setReceiptData to set it.", 1);
        }

        $signInterface = $this->getSignService(self::NORMAL_SIGN_TYPE);

        $this->signature = $signInterface->sign($receiptData ?? $this->receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    /**
     * Sign cancel receipt; obtain signature and QR code
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function cancelSign(ReceiptData $receiptData = null)
    {
        if ($receiptData == null && $this->receiptData == null) {
            throw new NoReceiptDataException("use ReceiptSigner@setReceiptData to set it.", 1);
        }

        $signInterface = $this->getSignService(self::CANCEL_SIGN_TYPE);

        $this->signature = $signInterface->sign($receiptData ?? $this->receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    /**
     * Sign training receipt; obtain signature and QR code
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function trainingSign(ReceiptData $receiptData = null)
    {
        if ($receiptData == null && $this->receiptData == null) {
            throw new NoReceiptDataException("use ReceiptSigner@setReceiptData to set it.", 1);
        }

        $signInterface = $this->getSignService(self::TRAINING_SIGN_TYPE);

        $this->signature = $signInterface->sign($receiptData ?? $this->receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    /**
     * Sign null (first) receipt; obtain signature and QR code
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function nullSign(ReceiptData $receiptData = null)
    {
        if ($receiptData == null && $this->receiptData == null) {
            throw new NoReceiptDataException("use ReceiptSigner@setReceiptData to set it.", 1);
        }

        $signInterface = $this->getSignService(self::NULL_SIGN_TYPE);

        $this->signature = $signInterface->sign($receiptData ?? $this->receiptData);
        $this->qr = $this->generateQRCode($this->signature);
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