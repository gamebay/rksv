<?php

namespace Gamebay\RKSV\Factory;

use Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException;
use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Providers\PrimeSignProvider;
use Gamebay\RKSV\Services\SignServices\SignServiceInterface;
use Gamebay\RKSV\Validators\SignatureType;

/**
 * Class SignServiceFactory
 * @package Factory
 */
class SignServiceFactory implements SignServiceFactoryInterface
{
    /** @var PrimeSignProvider $provider */
    private PrimeSignProvider $provider;

    /** @var ReceiptData */
    private ReceiptData $receiptData;

    /** @var string */
    private string $encryptionKey;

    /** @var string */
    private string $tokenKey;

    /** @var array */
    private array $taxRates;

    /** @var string */
    private string $locationId;

    /**
     * SignServiceFactory constructor.
     * @param ReceiptData $receiptData
     * @param string $primeSignBaseCertificateURL
     * @param string $primeSignReceiptSignURL
     * @param string $primeSignTokenKey
     * @param string $encryptionKey
     * @param string $tokenKey
     * @param array $taxRates
     * @param string $locationId
     */
    public function __construct(
        ReceiptData $receiptData,
        string $primeSignBaseCertificateURL,
        string $primeSignReceiptSignURL,
        string $primeSignTokenKey,
        string $encryptionKey,
        string $tokenKey,
        array $taxRates,
        string $locationId
    ) {
        $this->provider = new PrimeSignProvider(
            $primeSignBaseCertificateURL,
            $primeSignReceiptSignURL,
            $primeSignTokenKey
        );
        $this->receiptData = $receiptData;
        $this->encryptionKey = $encryptionKey;
        $this->tokenKey = $tokenKey;
        $this->taxRates = $taxRates;
        $this->locationId = $locationId;
    }

    /**
     * @param SignatureType $signatureType
     * @return SignServiceInterface
     * @throws InvalidSignTypeException
     */
    public function create(SignatureType $signatureType): SignServiceInterface
    {
        $classTarget = 'Gamebay\RKSV\Services\SignServices\\' . $signatureType->getInstanceOf() . 'SignService';

        if (!\class_exists($classTarget)) {
            throw new InvalidSignTypeException();
        }

        /** @var SignServiceInterface $signService */
        return new $classTarget(
            $this->provider,
            $this->receiptData,
            $this->encryptionKey,
            $this->tokenKey,
            $this->taxRates,
            $this->locationId
        );
    }
}