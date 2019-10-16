<?php


namespace Gamebay\RKSV\Services\SignServices;


use GuzzleHttp\Psr7\Request;

/**
 * Interface SignServiceInterface
 * @package Gamebay\RKSV\Services\SignServices
 */
interface SignServiceInterface
{

    /**
     * @return Request
     */
    public function sign(): Request;
}