<?php


namespace Gamebay\RKSV\Models;


use Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidItemException;

/**
 * Class ReceiptData
 * @package Models
 */
class ReceiptData
{
    /** @var string $cashboxId */
    private $cashboxId;

    /** @var string $salesCounter */
    private $salesCounter;

    /** @var string $receiptId */
    private $receiptId;

    /** @var \DateTime $receiptTimestamp */
    private $receiptTimestamp;

    /** @var array $items */
    private $items;

    /** @var string $previousReceiptSignature */
    private $previousReceiptSignature;

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
     * @param string $cashboxId
     * @param string $salesCounter
     * @param string $receiptId
     * @param \DateTime $receiptTimestamp
     * @param array $items
     * @param string $previousReceiptSignature
     * @return ReceiptData
     * @throws InvalidItemException
     */
    public static function withData(
        string $cashboxId,
        string $salesCounter,
        string $receiptId,
        \DateTime $receiptTimestamp,
        array $items,
        string $previousReceiptSignature
    ) {
        $receiptData = new self();

        $receiptData->cashboxId = $cashboxId;
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
    public function setCashboxId(string $id)
    {
        $this->cashboxId = $id;
    }

    /**
     * @return string
     */
    public function getCashboxId()
    {
        return $this->cashboxId;
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
    public function getSalesCounter()
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
    public function getReceiptId()
    {
        return $this->receiptId;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setReceiptTimestamp(\DateTime $timestamp)
    {
        $this->receiptTimestamp = $timestamp->format('Y-m-d\TH:i:s');
    }

    /**
     * @return string
     */
    public function getReceiptTimestamp()
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
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add an item with $net,$tax to the $items list
     *
     * @param float $net
     * @param float $tax
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
     * Remove item with specified $net,$tax from the $items list
     * @param $net
     * @param $tax
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
     * Sums the items' brutto values into tax groups given
     * @param array $taxes
     * @return array
     */
    public function sumItemsByTaxes(array $taxes)
    {
        $zeros = array_fill(0, count($taxes), 0);
        $taxValues = array_combine($taxes, $zeros);

        // TODO handle 'special' without referencing 'special'
        foreach ($this->items as $item) {
            $taxValues[strval($item['tax'])] += $item['brutto'];
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
    public function getPreviousReceiptSignature()
    {
        return $this->previousReceiptSignature;
    }
}