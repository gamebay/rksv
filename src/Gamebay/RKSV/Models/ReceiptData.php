<?php


namespace Gamebay\RKSV\Models;


use Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidItemException;

/**
 * Class ReceiptData
 * @package Models
 */
class ReceiptData
{
    private $cashboxId;
    private $receiptId;
    private $receiptTimestamp;
    private $items;
    private $previousReceiptCompactSignature;

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
     * @param string $receiptId
     * @param \DateTime $receiptTimestamp
     * @param array $items
     * @param string $previousReceiptCompactSignature
     *
     * @throws InvalidItemException
     *
     * @return ReceiptData
     */
    public static function withData(
        string $cashboxId,
        string $receiptId,
        \DateTime $receiptTimestamp,
        array $items,
        string $previousReceiptCompactSignature)
    {
        $self = new self();

        $self->cashboxId = $cashboxId;
        $self->receiptId = $receiptId;
        $self->receiptTimestamp = $receiptTimestamp;
        if ($self->validateItemsArray($items)) {
            $self->items = $items;
        } else {
            throw new InvalidItemException();
        }
        $self->previousReceiptCompactSignature = $previousReceiptCompactSignature;

        return $self;
    }

    /**
     * Validate if item is of right form
     * @param array $item
     * @return bool
     */
    private function validateItem(array $item)
    {
        return isset($item['net']) && is_numeric($item['net'])
            && isset($item['tax']) && is_numeric($item['tax'])
            && $item['tax'] >=0 && $item['tax'] <= 100;
    }

    /**
     * Validate if array of items has all items valid
     * @param array $items
     * @return bool
     */
    public function validateItemsArray(array $items)
    {
        return count($items) == count(array_filter($items, 'validateItem'));
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
        $this->receiptTimestamp = $timestamp->format('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public function getReceiptTimestamp()
    {
        return $this->receiptTimestamp;
    }

    /**
     * @param array $items
     *
     * @throws InvalidItemException
     */
    public function setItems(array $items)
    {
        if ($this->validateItemsArray($items)) {
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
    public function addItem(float $net, float $tax)
    {
        $item = [
            'net' => $net,
            'tax' => $tax
        ];
        if ($this->validateItem($item)) {
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
    public function removeItem($net, $tax)
    {
        $foundKey = -1;
        foreach ($this->items as $key => $item) {
            if ($item['net'] == $net && $item['tax'] == $tax) {
                $foundKey = $key;
                break;
            }
        }
        if ($foundKey > -1) {
            unset($this->items[$foundKey]);
        }
    }

    /**
     * @param string $signature
     */
    public function setPreviousReceiptCompactSignature(string $signature)
    {
        $this->previousReceiptCompactSignature = $signature;
    }

    /**
     * @return string
     */
    public function getPreviousReceiptCompactSignature()
    {
        return $this->previousReceiptCompactSignature;
    }
}