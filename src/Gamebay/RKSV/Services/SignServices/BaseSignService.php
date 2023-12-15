<?php

namespace Gamebay\RKSV\Services\SignServices;

use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Providers\PrimeSignProvider;
use Gamebay\RKSV\Services\Encrypter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Class BaseSignService
 * @package Gamebay\Rksv\Services\SignServices
 */
class BaseSignService
{
    /** @var PrimeSignProvider $provider */
    protected PrimeSignProvider $provider;

    /** @var ReceiptData */
    protected ReceiptData $receiptData;

    /** @var Encrypter $encrypter */
    protected Encrypter $encrypter;

    /** @var string $tokenKey */
    protected string $tokenKey;

    /** @var array $taxRates */
    protected array $taxRates;

    /** @var string $locationId */
    protected string $locationId;

    /**
     * @param PrimeSignProvider $provider
     * @param ReceiptData $receiptData
     * @param string $encryptionKey
     * @param string $tokenKey
     * @param array $taxRates
     * @param string $locationId
     */
    public function __construct(
        PrimeSignProvider $provider,
        ReceiptData $receiptData,
        string $encryptionKey,
        string $tokenKey,
        array $taxRates,
        string $locationId
    ) {
        $this->provider = $provider;
        $this->receiptData = $receiptData;
        $this->encrypter = new Encrypter($encryptionKey);
        $this->tokenKey = $tokenKey;
        $this->taxRates = $taxRates;
        $this->locationId = $locationId;
    }

    /**
     * @param string $compactReceiptData
     * @return Response
     * @throws GuzzleException
     */
    public function sign(string $compactReceiptData): Response
    {
        $headers = [
            'X-AUTH-TOKEN' => $this->tokenKey,
            'Content-Type' => 'text/plain;charset=UTF-8',
        ];

        $httpClientRequest = new Request('POST', $this->provider->fullSignerUrl, $headers, $compactReceiptData);
        $client = new Client();
        return $client->send($httpClientRequest);
    }

    /**
     * @param string $primeSignCertificateNumber
     * @return string
     */
    public function generateCompactReceiptData(string $primeSignCertificateNumber, string $salesCounterType='normal'): string
    {
        if ($salesCounterType == 'normal') {
            $encryptedSalesCounter = $this->encrypter->encryptSalesCounter($this->receiptData);        
        } else if ($salesCounterType == 'null') {
            $nullItem = [
                [
                  'brutto' => 0,
                  'tax' => 0
                ]
            ];
            $this->receiptData->setItems($nullItem);
            $this->receiptData->setSalesCounter(0.00);
            $this->receiptData->setPreviousReceiptSignature($this->receiptData->getCashboxId());
            $encryptedSalesCounter = $this->encrypter->encryptSalesCounter($this->receiptData);        
        } else {  // if training or storno
            $encryptedSalesCounter = $salesCounterType;
        }

        $taxValues = implode('_', $this->receiptData->sumItemsByTaxes($this->taxRates));
        $previousCompactSignature = $this->encrypter->getCompactSignature($this->receiptData->getPreviousReceiptSignature());

        return '_R1-' . $this->locationId .
        '_' . $this->receiptData->getCashboxId() .
        '_' . $this->receiptData->getReceiptId() .
        '_' . $this->receiptData->getReceiptTimestamp() .
        '_' . $taxValues .
        '_' . $encryptedSalesCounter .
        '_' . $primeSignCertificateNumber .
        '_' . $previousCompactSignature;
    }
}