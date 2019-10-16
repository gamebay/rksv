<?php


namespace Gamebay\RKSV\Services;

use Gamebay\RKSV\ErrorHandlers\Exceptions\NoReceiptDataException;
use Illuminate\Http\Response;
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
        $signatureType = self::NORMAL_SIGN_TYPE;

        $receiptData = $this->isReceiptDataSet($receiptData);

        $signInterface = $this->getSignService($signatureType);

        $this->signature = $signInterface->sign($receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    /**
     * Sign cancel receipt; obtain signature and QR code
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function cancelSign(ReceiptData $receiptData = null)
    {
        $signatureType = self::CANCEL_SIGN_TYPE;

        $receiptData = $this->isReceiptDataSet($receiptData);

        $signInterface = $this->getSignService($signatureType);

        $this->signature = $signInterface->sign($receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    /**
     * Sign training receipt; obtain signature and QR code
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function trainingSign(ReceiptData $receiptData = null)
    {
        $signatureType = self::TRAINING_SIGN_TYPE;

        $receiptData = $this->isReceiptDataSet($receiptData);

        $signInterface = $this->getSignService($signatureType);

        $this->signature = $signInterface->sign($receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    /**
     * Sign null (first) receipt; obtain signature and QR code
     * @param ReceiptData|null $receiptData
     * @throws NoReceiptDataException
     */
    public function nullSign(ReceiptData $receiptData = null)
    {
        $signatureType = self::NULL_SIGN_TYPE;

        $receiptData = $this->isReceiptDataSet($receiptData);

        $signInterface = $this->getSignService($signatureType);

        $this->signature = $signInterface->sign($receiptData);
        $this->qr = $this->generateQRCode($this->signature);

    }

    /**
     * @param ReceiptData $receiptData
     * @return ReceiptData
     * @throws NoReceiptDataException
     */
    private function isReceiptDataSet(ReceiptData $receiptData): ReceiptData
    {
        $receiptData ?: $receiptData = $this->receiptData;

        if (null === $receiptData) {
            throw new NoReceiptDataException();
        }

        return $receiptData;
    }
}