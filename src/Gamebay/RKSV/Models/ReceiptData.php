<?php

namespace Gamebay\RKSV\Models;

use DateTime;
use Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidItemException;

/**
 * Class ReceiptData
 * @package Models
 */
class ReceiptData
{
    /** @var string $cashBoxId */
    private string $cashBoxId;

    /** @var string $salesCounter */
    private string $salesCounter;

    /** @var string $receiptId */
    private string $receiptId;

    /** @var DateTime $receiptTimestamp */
    private DateTime $receiptTimestamp;

    /** @var array $items */
    private array $items;

    /** @var string $previousReceiptSignature */
    private string $previousReceiptSignature;

    /**
     * ReceiptData constructor.
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Construct ReceiptData with provided data
     *
     * @param string $cashBoxId
     * @param string $salesCounter
     * @param string $receiptId
     * @param DateTime $receiptTimestamp
     * @param array $items
     * @param string $previousReceiptSignature
     * @return ReceiptData
     * @throws InvalidItemException
     */
    public static function withData(
        string $cashBoxId,
        string $salesCounter,
        string $receiptId,
        DateTime $receiptTimestamp,
        array $items,
        string $previousReceiptSignature
    ): ReceiptData
    {
        $receiptData = new self();

        $receiptData->cashBoxId = $cashBoxId;
        $receiptData->salesCounter = $salesCounter;
        $receiptData->receiptId = $receiptId;
        $receiptData->receiptTimestamp = $receiptTimestamp;
        if (self::isValidItemsArray($items)) {
            $receiptData->items = $items;
        } else {
            throw new InvalidItemException();
        }
        $receiptData->previousReceiptSignature = $previousReceiptSignature;

        return $receiptData;
    }

    /**
     * Validate if item is of right form
     * @param array $item
     * @return bool
     */
    public static function isValidItem(array $item)
    {
        return isset($item['brutto']) && is_numeric($item['brutto'])
            && isset($item['tax']) && is_int($item['tax'])
            && $item['tax'] >= 0 && $item['tax'] <= 100;
    }

    /**
     * Validate if array of items has all items valid
     * @param array $items
     * @return bool
     */
    public static function isValidItemsArray(array $items)
    {
        foreach ($items as $item) {
            if (!self::isValidItem($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $id
     */
    public function setCashBoxId(string $id)
    {
        $this->cashBoxId = $id;
    }

    /**
     * @return string
     */
    public function getCashBoxId(): string
    {
        return $this->cashBoxId;
    }

    /**
     * @param string $salesCounter
     */
    public function setSalesCounter(string $salesCounter)
    {
        $this->salesCounter = $salesCounter;
    }

    /**
     * @return string
     */
    public function getSalesCounter(): string
    {
        return $this->salesCounter;
    }

    /**
     * @param string $id
     */
    public function setReceiptId(string $id)
    {
        $this->receiptId = $id;
    }

    /**
     * @return string
     */
    public function getReceiptId(): string
    {
        return $this->receiptId;
    }

    /**
     * @param DateTime $timestamp
     */
    public function setReceiptTimestamp(DateTime $timestamp)
    {
        $this->receiptTimestamp = $timestamp;
    }

    /**
     * @return string
     */
    public function getReceiptTimestamp(): string
    {
        return $this->receiptTimestamp->format('Y-m-d\TH:i:s');
    }

    /**
     * @param array $items
     *
     * @throws InvalidItemException
     */
    public function setItems(array $items)
    {
        if (self::isValidItemsArray($items)) {
            $this->items = $items;
        } else {
            throw new InvalidItemException();
        }
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Add an item with $brutto, $tax to the $items list.
     *
     * @param float $brutto
     * @param int $tax
     *
     * @throws InvalidItemException
     */
    public function addItem(float $brutto, int $tax)
    {
        $item = [
            'brutto' => $brutto,
            'tax' => $tax
        ];
        if (self::isValidItem($item)) {
            $this->items[] = $item;
        } else {
            throw new InvalidItemException();
        }
    }

    /**
     * Remove item with specified $brutto, $tax from the $items list.
     *
     * @param float $brutto
     * @param int $tax
     */
    public function removeItem(float $brutto, int $tax)
    {
        $foundKey = -1;
        foreach ($this->items as $key => $item) {
            if ($item['brutto'] == $brutto && $item['tax'] == $tax) {
                $foundKey = $key;
                break;
            }
        }
        if ($foundKey > -1) {
            unset($this->items[$foundKey]);
        }
    }

    /**
     * Sums the items' brutto values into tax groups given.
     *
     * @param array $taxes
     * @return array
     */
    public function sumItemsByTaxes(array $taxes): array
    {
        $zeros = array_fill(0, count($taxes), 0);
        $taxValues = array_combine($taxes, $zeros);

        // TODO => Handle 'special' without referencing 'special'
        foreach ($this->items as $item) {
            $taxValues[strval($item['tax'])] += $item['brutto'];
        }
        foreach ($taxValues as $key => $taxValue) {
            $taxValues[$key] = number_format((float)$taxValue, 2, ',', '');
        }

        return $taxValues;
    }

    /**
     * @param string $signature
     */
    public function setPreviousReceiptSignature(string $signature)
    {
        $this->previousReceiptSignature = $signature;
    }

    /**
     * @return string
     */
    public function getPreviousReceiptSignature(): string
    {
        return $this->previousReceiptSignature;
    }
}