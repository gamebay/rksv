<?php


use PHPUnit\Framework\TestCase;
use Gamebay\RKSV\Models\ReceiptData;

class ReceiptDataTest extends TestCase
{
    public function testIsValidItem()
    {
        $receiptData = new ReceiptData();

        $item =  [
            'net' => 100,
            'tax' => 20
        ];
        $this->assertTrue($receiptData->isValidItem($item));

        $items = [
            'net' => 100,
            'tax' => 101
        ];
        $this->assertFalse($receiptData->isValidItem($items));

        $item = [
            'tax' => 90
        ];
        $this->assertFalse($receiptData->isValidItem($item));
    }
}