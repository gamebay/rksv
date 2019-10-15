<?php


namespace Gamebay\RKSV\Services;


class ReceiptSigner
{

    public function __construct(ReceiptData $receiptData)
    {
        $this->receiptData = $receiptData;
    }

    private $receiptData;

    const NORMAL_SIGN_TYPE = 'normal';
    const CANCEL_SIGN_TYPE = 'storno';
    const TRAINING_SIGN_TYPE = 'training';
    const NULL_SIGN_TYPE = 'null';

    CONST SIGN_TYPE = [
        self::CANCEL_SIGN_TYPE => 'U1RP',
        self::TRAINING_SIGN_TYPE => 'VFJB',
        self::NORMAL_SIGN_TYPE => '',
        self::NULL_SIGN_TYPE => '',
    ];

    //todo: create ReceiptData $receiptData validation object which is expected in the methods below
    public function NormalSign(){

        //
    }

}