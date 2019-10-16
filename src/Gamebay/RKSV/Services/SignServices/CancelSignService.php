<?php


namespace Gamebay\RKSV\Services\SignServices;


use Gamebay\RKSV\Models\ReceiptData;
use Gamebay\Rksv\Providers\PrimeSignProvider;
use GuzzleHttp\Psr7\Request;

/**
 * Class CancelSignService
 * @package Gamebay\RKSV\Services\SignServices
 */
class CancelSignService implements SingServiceInterface
{

    /**
     * @var PrimeSignProvider $provider
     */
    private $provider;

    /**
     * @var ReceiptData
     */
    private $receiptData;

    /**
     * CancelSignService constructor.
     * @param PrimeSignProvider $provider
     * @param ReceiptData $receiptData
     */
    public function __construct(PrimeSignProvider $provider, ReceiptData $receiptData)
    {
        $this->provider = $provider;
        $this->receiptData = $receiptData;
    }

    /**
     * @return Request
     */
    public function sign(): Request
    {
        $headers = [
            'X-AUTH-TOKEN' => config('rksv_primesign_token_key'),
            'Content-Type' => 'text/plain;charset=UTF-8',
        ];

        $locationId = config('rksv_primesign_location_id');

        $taxes = $this->getTaxValuesFromItems($this->receiptData->getItems());

        $body = '_R1-' . $locationId . '_' . $this->receiptData->getCashboxId() . '_' . $this->receiptData->getReceiptId() . '_' . $this->receiptData->getReceiptTimestamp() . '_' . $taxes . '_' . $this->receiptData->getTurnoverCounter . '_' . config('rksv_primesign_certificate_number') . '_' . $this->receiptData->getPreviousReceiptCompactSignature();

        $httpClientRequest = new Request('POST', $this->provider->fullSignerUrl, $headers, $body);

        return $httpClientRequest;
    }

    /**
     * @param array $items
     * @return array
     */
    private function getTaxValuesFromItems(array $items)
    {

        $taxValues = [
            '20' => 0,
            '10' => 0,
            '13' => 0,
            '0' => 0,
            'special' => 0
        ];

        foreach ($items as $item) {
            $taxValues[$item['tax']] += $item['brutto'];
        }

        return implode('_', $taxValues);
    }
}