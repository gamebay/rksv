<?php


namespace Gamebay\Rksv\Services\SignServices;


use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\RKSV\Providers\PrimeSignProvider;
use Gamebay\RKSV\Services\Encrypter;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle6\Client;

/**
 * Class BaseSignService
 * @package Gamebay\Rksv\Services\SignServices
 */
class BaseSignService
{
    /** @var PrimeSignProvider $provider */
    protected $provider;

    /** @var ReceiptData */
    protected $receiptData;

    /** @var string $tokenKey */
    protected $tokenKey;

    /** @var string $taxRates */
    protected $taxRates;
    /**
     * @var string $locationId
     */
    protected $locationId;

    /** @var Encrypter $encrypter */
    protected $encrypter;

    /**
     * CancelSignService constructor.
     * @param PrimeSignProvider $provider
     * @param ReceiptData $receiptData
     * @param Encrypter $encrypter
     * @param string|null $tokenKey
     * @param string|null $taxRates
     * @param string $locationId
     */
    public function __construct(PrimeSignProvider $provider, ReceiptData $receiptData, string $tokenKey = null, string $taxRates = null, string $locationId = null)
    {
        $this->provider = $provider;
        $this->receiptData = $receiptData;
        $this->encrypter = new Encrypter();


        isset($tokenKey) ? $this->tokenKey = $tokenKey : $this->tokenKey = config('RKSV.rksv_primesign_token_key');
        isset($taxRates) ? $this->taxRates = $taxRates : $this->taxRates = config('RKSV.taxes');
        isset($locationId) ? $this->locationId = $locationId : $this->locationId = config('RKSV.rksv_primesign_location_id');

    }

    /**
     * @param string $compactReceiptData
     * @return Response
     */
    public function sign(string $compactReceiptData): Response
    {

        $headers = [
            'X-AUTH-TOKEN' => $this->tokenKey,
            'Content-Type' => 'text/plain;charset=UTF-8',
        ];


        $httpClientRequest = new Request('POST', $this->provider->fullSignerUrl, $headers, $compactReceiptData);
        $client = new \GuzzleHttp\Client();
        $response = $client->send($httpClientRequest);

        return $response;
    }

    /**
     * @return string
     */
    public function generateCompactReceiptData(): string
    {
        $taxValues = implode('_', $this->receiptData->sumItemsByTaxes($this->taxRates));

        $encryptedSalesCounter = $this->encrypter->encryptSalesCounter($this->receiptData);
        $previousCompactSignature = $this->encrypter->getCompactSignature($this->receiptData->getPreviousReceiptSignature());

        $compactSignature =
            '_R1-' . $this->locationId .
            '_' . $this->receiptData->getCashboxId() .
            '_' . $this->receiptData->getReceiptId() .
            '_' . $this->receiptData->getReceiptTimestamp() .
            '_' . $taxValues .
            '_' . $encryptedSalesCounter .
            '_' . config('RKSV.rksv_primesign_certificate_number') .
            '_' . $previousCompactSignature;

        return $compactSignature;
    }
}