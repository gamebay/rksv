<?php


namespace Gamebay\RKSV\Factory;


use Validators\SignatureType;

/**
 * Interface SignServiceFactoryInterface
 * @package Gamebay\RKSV\Factory
 */
interface SignServiceFactoryInterface
{
    /**
     * @param SignatureType $signatureType
     * @return mixed
     */
    public function create(SignatureType $signatureType);
}