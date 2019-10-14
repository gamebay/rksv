<?php


namespace Services;


class ReceiptSigner
{

    public function __construct(ReceiptData $receiptData)
    {
        $this->receiptData = $receiptData;
    }

    private $receiptData;

    CONST NORMAL_SIGN_TYPE = 'normal';
    CONST CANCEL_SIGN_TYPE = 'storno';
    CONST

    CONST SIGN_TYPE = [

    ];
}