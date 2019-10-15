<?php


namespace Gamebay\Rksv\Factory;


use Validators\SignatureType;

/**
 * Interface SignServiceFactoryInterface
 * @package Gamebay\Rksv\Factory
 */
interface SignServiceFactoryInterface
{
    /**
     * @param SignatureType $signatureType
     * @return mixed
     */
    public function create(SignatureType $signatureType);
}