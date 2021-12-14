<?php

namespace Gamebay\RKSV\Services\SignServices;

use GuzzleHttp\Psr7\Response;

/**
 * Interface SignServiceInterface
 * @package Gamebay\RKSV\Services\SignServices
 */
interface SignServiceInterface
{
    /**
     * @param string $compactReceiptData
     * @return Response
     */
    public function sign(string $compactReceiptData): Response;

    /**
     * @param string $primeSignCertificateNumber
     * @return string
     */
    public function generateCompactReceiptData(string $primeSignCertificateNumber): string;
}