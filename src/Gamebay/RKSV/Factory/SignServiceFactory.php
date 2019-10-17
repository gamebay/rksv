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
    private $provider;

    /** @var ReceiptData */
    private $receiptData;

    /**
     * SignServiceFactory constructor.
     * @param ReceiptData $receiptData
     */
    public function __construct(ReceiptData $receiptData)
    {
        $this->provider = new PrimeSignProvider();
        $this->receiptData = $receiptData;
    }

    /**
     * @param SignatureType $signatureType
     * @return SignServiceInterface
     * @throws InvalidSignTypeException
     */
    public function create(SignatureType $signatureType): SignServiceInterface
    {
        $classTarget = $signatureType->getInstanceOf() . 'SignService';

        if (!class_exists($classTarget)) {
            throw new InvalidSignTypeException();
        }

        /** @var SignServiceInterface $signService */
        $signService = new $classTarget($this->provider, $this->receiptData);

        return $signService;

    }

}