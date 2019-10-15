<?php


namespace Factory;

use Gamebay\Rksv\ErrorHandlers\Exceptions\InvalidSignTypeException;
use Gamebay\Rksv\Factory\SignServiceFactoryInterface;
use Gamebay\RKSV\Services\SignServices\SingServiceInterface;
use Validators\SignatureType;

/**
 * Class SignServiceFactory
 * @package Factory
 */
class SignServiceFactory implements SignServiceFactoryInterface
{

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
        $signService = new $classTarget();

        return $signService;

    }

}