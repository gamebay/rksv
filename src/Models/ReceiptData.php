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

    public static function withData($cashboxId, $receiptId, $receiptTimestamp, $items, $previousReceiptCompactSignature)
    {
        $self = new self();

        $self->cashboxId = $cashboxId;
        $self->receiptId = $receiptId;
        $self->receiptTimestamp = $receiptTimestamp;
        $self->items = $items;
        $self->previousReceiptCompactSignature = $previousReceiptCompactSignature;

        return $self;
    }

    public function setCashboxId($id)
    {
        $this->cashboxId = $id;
    }
    public function getCashboxId()
    {
        return $this->cashboxId;
    }

    public function setReceiptId($id)
    {
        $this->receiptId = $id;
    }
    public function getReceiptId()
    {
        return $this->receiptId;
    }

    public function setReceiptTimestamp($timestamp)
    {
        try {
            $timestamp = Carbon::parse($timestamp);
        } catch (\Exception $e) {
            dd('Not a valid timestamp: ' . $e);
        }
        $this->receiptTimestamp = $timestamp->toDateTimeString();
    }
    public function getReceiptTimestamp()
    {
        return $this->receiptTimestamp;
    }

    public function setItems(array $items)
    {
        foreach ($items as $item) {
            // TODO
            if (!count($item) != 2 || !isset($item['net']) || !isset($item['tax']))
                dd('Wrong item: ' . $item);
        }
        $this->items = $items;
    }
    public function getItems()
    {
        return $this->items;
    }
    public function addItem($net, $tax)
    {
        // TODO
        if (!is_numeric($net) || !is_numeric($tax) || $tax >= 100)
            dd('Wrong item');

        $this->items[] = [ 'net' => $net, 'tax' => $tax ];
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

    public function setPreviousReceiptCompactSignature($signature)
    {
        $this->previousReceiptCompactSignature = $signature;
    }
    public function getPreviousReceiptCompactSignature()
    {
        return $this->previousReceiptCompactSignature;
    }
}