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
    private ReceiptData $receiptData;
    private ?string $signature;
    private ?string $qr;

    private string $primeSignBaseCertificateURL;
    private string $primeSignReceiptSignURL;
    private string $primeSignTokenKey;
    private string $primeSignCertificateNumber;
    private string $encryptionKey;
    private array $taxRates;
    private string $locationId;

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
    public function getSignService(string $signType): SignServices\SignServiceInterface
    {
        $this->isReceiptDataSet($this->receiptData);

        $signatureType = new SignatureType($signType);
        $signServiceFactory = new SignServiceFactory(
            $this->receiptData,
            $this->primeSignBaseCertificateURL,
            $this->primeSignReceiptSignURL,
            $this->primeSignTokenKey,
            $this->encryptionKey,
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
    public function generateQRCodeString(string $compactReceiptData, string $signature): string
    {
        $encrypter = new Encrypter($this->encryptionKey);

        $signature = $encrypter->base64url_decode($signature);
        $signature = base64_encode($signature);

        return (new QRCode())->render($compactReceiptData . '_' . $signature);
    }


    public function extractSignatureForQRCode($jws) {
        // Split the JWS into its three parts
        $jwsParts = explode('.', $jws);
    
        // Check if the JWS has three parts (header, payload, signature)
        if (count($jwsParts) !== 3) {
            throw new Exception("Invalid JWS: The token does not have three parts.");
        }
        // The signature is the third part of the JWS and is already in Base64Url format
        $signature = $jwsParts[2];    
        // Return the signature part of the JWS
        return $signature;
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
        $qr_signature = $this->extractSignatureForQRCode($this->signature);
        $this->qr = $this->generateQRCodeString($compactReceiptData, $qr_signature);
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
     * @param ReceiptData|null $receiptData
     * @return void
     * @throws NoReceiptDataException
     */
    private function isReceiptDataSet(ReceiptData $receiptData = null): void
    {
        $receiptData ?: $receiptData = $this->receiptData;

        if (null === $receiptData) {
            throw new NoReceiptDataException();
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
    public function getReceiptData(): ReceiptData
    {
        return $this->receiptData;
    }

    /**
     * @return null|string
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * @return null|string
     */
    public function getQR(): ?string
    {
        return $this->qr;
    }
}
