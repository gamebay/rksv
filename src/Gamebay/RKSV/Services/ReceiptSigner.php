<?php


namespace Gamebay\RKSV\Services;

use chillerlan\QRCode\QRCode;
use Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException;
use Gamebay\RKSV\Factory\SignServiceFactory;
use Gamebay\RKSV\ErrorHandlers\Exceptions\NoReceiptDataException;
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

    private $primeSignBaseCertificateURL;
    private $primeSignReceiptSignURL;
    private $primeSignTokenKey;
    private $primeSignCertificateNumber;
    private $encryptionKey;
    private $tokenKey;
    private $taxRates;
    private $locationId;

    const NORMAL_SIGN_TYPE = 'normal';
    const CANCEL_SIGN_TYPE = 'storno';
    const TRAINING_SIGN_TYPE = 'training';
    const NULL_SIGN_TYPE = 'null';

    /**
     * ReceiptSigner constructor.
     * @param string $primeSignBaseCertificateURL
     * @param string $primeSignReceiptSignURL
     * @param string $primeSignTokenKey
     * @param string $primeSignCertificateNumber
     * @param string $encryptionKey
     * @param string $tokenKey
     * @param array $taxRates
     * @param string $locationId
     * @param ReceiptData|null $receiptData
     */
    public function __construct(
        string $primeSignBaseCertificateURL,
        string $primeSignReceiptSignURL,
        string $primeSignTokenKey,
        string $primeSignCertificateNumber,
        string $encryptionKey,
        string $tokenKey,
        array $taxRates,
        string $locationId,
        ReceiptData $receiptData = null
    )
    {
        $this->primeSignBaseCertificateURL = $primeSignBaseCertificateURL;
        $this->primeSignReceiptSignURL = $primeSignReceiptSignURL;
        $this->primeSignTokenKey = $primeSignTokenKey;
        $this->primeSignCertificateNumber = $primeSignCertificateNumber;
        $this->encryptionKey = $encryptionKey;
        $this->tokenKey = $tokenKey;
        $this->taxRates = $taxRates;
        $this->locationId = $locationId;
        if ($receiptData != null) {
            $this->receiptData = $receiptData;
        }
    }

    /**
     * Get appropriate Sign Service.
     *
     * @param string $signType
     * @return SignServices\SignServiceInterface
     * @throws NoReceiptDataException
     * @throws InvalidSignTypeException
     */
    public function getSignService(string $signType)
    {
        $this->isReceiptDataSet($this->receiptData);

        $signatureType = new SignatureType($signType);
        $signServiceFactory = new SignServiceFactory(
            $this->receiptData,
            $this->primeSignBaseCertificateURL,
            $this->primeSignReceiptSignURL,
            $this->primeSignTokenKey,
            $this->encryptionKey,
            $this->tokenKey,
            $this->taxRates,
            $this->locationId
        );

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
     * @throws InvalidSignTypeException
     */
    private function sign(string $signType)
    {
        $signInterface = $this->getSignService($signType);

        $compactReceiptData = $signInterface->generateCompactReceiptData($this->primeSignCertificateNumber);

        $response = $signInterface->sign($compactReceiptData);
        $this->signature = $response->getBody()->getContents();
        $this->qr = $this->generateQRCodeString($compactReceiptData, $this->signature);
    }

    /**
     * Helper for normal sign.
     * @throws NoReceiptDataException
     * @throws InvalidSignTypeException
     */
    public function normalSign()
    {
        $this->sign(self::NORMAL_SIGN_TYPE);
    }

    /**
     * Helper for cancel/storno sign.
     * @throws NoReceiptDataException
     * @throws InvalidSignTypeException
     */
    public function cancelSign()
    {
        $this->sign(self::CANCEL_SIGN_TYPE);
    }

    /**
     * Helper for training sign.
     * @throws NoReceiptDataException
     * @throws InvalidSignTypeException
     */
    public function trainingSign()
    {
        $this->sign(self::TRAINING_SIGN_TYPE);
    }

    /**
     * Helper for null/first sign.
     * @throws NoReceiptDataException
     * @throws InvalidSignTypeException
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