<?php


namespace Models;


use Carbon\Carbon;

class ReceiptData
{
    private $cashboxId;
    private $receiptId;
    private $receiptTimestamp;
    private $items;
    private $previousReceiptCompactSignature;

    public function __construct()
    {
        $this->items = [];
    }

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
            // TODO error handler...
            dd('wrong items');
        }
        $self->previousReceiptCompactSignature = $previousReceiptCompactSignature;

        return $self;
    }

    private function validateItem($item)
    {
        return isset($item['net']) && is_numeric($item['net'])
            && isset($item['tax']) && is_numeric($item['tax'])
            && $item['tax'] >=0 && $item['tax'] <= 100;
    }

    public function validateItemsArray($items)
    {
        return count($items) == count(array_filter($items, 'validateItem'));
    }

    public function setCashboxId(string $id)
    {
        $this->cashboxId = $id;
    }
    public function getCashboxId()
    {
        return $this->cashboxId;
    }

    public function setReceiptId(string $id)
    {
        $this->receiptId = $id;
    }
    public function getReceiptId()
    {
        return $this->receiptId;
    }

    public function setReceiptTimestamp(\DateTime $timestamp)
    {
        $this->receiptTimestamp = $timestamp->format('Y-m-d H:i:s');
    }
    public function getReceiptTimestamp()
    {
        return $this->receiptTimestamp;
    }

    public function setItems(array $items)
    {
        if ($this->validateItemsArray($items)) {
            $this->items = $items;
        } else {
            // TODO error handler...
            dd('wrong item');
        }
    }
    public function getItems()
    {
        return $this->items;
    }
    public function addItem(float $net, float $tax)
    {
        $item = [
            'net' => $net,
            'tax' => $tax
        ];
        if ($this->validateItem($item)) {
            $this->items[] = $item;
        } else {
            // TODO error handler...
            dd('wrong item');
        }
    }
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

    public function setPreviousReceiptCompactSignature(string $signature)
    {
        $this->previousReceiptCompactSignature = $signature;
    }
    public function getPreviousReceiptCompactSignature()
    {
        return $this->previousReceiptCompactSignature;
    }
}