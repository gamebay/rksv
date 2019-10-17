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
     * @param string $compactReceiptData
     * @return Request
     */
    public function sign(string $compactReceiptData): Request;
    
    /**
     * @return string
     */
    public function generateCompactReceiptData(): string;
}