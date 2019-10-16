<?php


namespace Gamebay\RKSV\Services\SignServices;


use GuzzleHttp\Psr7\Request;

/**
 * Interface SingServiceInterface
 * @package Gamebay\RKSV\Services\SignServices
 */
interface SingServiceInterface
{

    /**
     * @return Request
     */
    public function sign(): Request;
}