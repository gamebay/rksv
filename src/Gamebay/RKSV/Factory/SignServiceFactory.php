<?php


namespace Gamebay\RKSV\Factory;

use Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException;
use Gamebay\RKSV\Factory\SignServiceFactoryInterface;
use Gamebay\Rksv\Providers\PrimeSignProvider;
use Gamebay\RKSV\Services\SignServices\SingServiceInterface;
use Validators\SignatureType;

/**
 * Class SignServiceFactory
 * @package Factory
 */
class SignServiceFactory implements SignServiceFactoryInterface
{

    /** @var PrimeSignProvider $provider */
    private $provider;

    /**
     * SignServiceFactory constructor.
     */
    public function __construct()
    {
        $this->provider = new PrimeSignProvider();
    }

    /**
     * @param SignatureType $signatureType
     * @return SingServiceInterface
     * @throws InvalidSignTypeException
     */
    public function create(SignatureType $signatureType): SingServiceInterface
    {
        $classTarget = $signatureType->getInstanceOf() . 'SignService';

        if (!class_exists($classTarget)) {
            throw new InvalidSignTypeException();
        }

        /** @var SingServiceInterface $signService */
        $signService = new $classTarget($this->provider);

        return $signService;

    }

}