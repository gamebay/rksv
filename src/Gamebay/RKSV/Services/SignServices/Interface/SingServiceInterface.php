<?php


namespace Gamebay\RKSV\Services\SignServices;


/**
 * Interface SingServiceInterface
 * @package Gamebay\RKSV\Services\SignServices
 */
interface SingServiceInterface
{

    /**
     * @return string
     */
    public function sign(): string;
}