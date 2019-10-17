<?php


use PHPUnit\Framework\TestCase;
use Gamebay\RKSV\Models\ReceiptData;

class ReceiptDataTest extends TestCase
{
    public function testIsValidItem()
    {
        $receiptData = new ReceiptData();

        $item =  [
            'brutto' => 100,
            'tax' => 20
        ];
        $this->assertTrue($receiptData->isValidItem($item));

        $items = [
            'brutto' => 100,
            'tax' => 101
        ];
        $this->assertFalse($receiptData->isValidItem($items));

        $item = [
            'tax' => 90
        ];
        $this->assertFalse($receiptData->isValidItem($item));
    }
}