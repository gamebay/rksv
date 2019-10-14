<?php


namespace Services;

use Models\ReceiptData;


class ReceiptSigner
{
    private $receiptData;
    private $signature;
    private $qr;

    const NORMAL_SIGN_TYPE = 'normal';
    const CANCEL_SIGN_TYPE = 'storno';
    const TRAINING_SIGN_TYPE = 'training';
    const NULL_SIGN_TYPE = 'null';

//    CONST SIGN_TYPE = [
//        self::CANCEL_SIGN_TYPE => 'U1RP',
//        self::TRAINING_SIGN_TYPE => 'VFJB',
//        self::NORMAL_SIGN_TYPE => '',
//        self::NULL_SIGN_TYPE => '',
//    ];

    public function __construct(ReceiptData $receiptData)
    {
        $this->receiptData = $receiptData;
        $this->signature = null;
        $this->qr = null;
    }

    public function getSignService($sign_type)
    {
        return SignServiceFactory::create($sign_type);
    }

    public function generateQRCode($signature)
    {
        return QRCodeService::create($signature);
    }

    public function normalSign()
    {
        $signInterface = $this->getSignService(self::NORMAL_SIGN_TYPE);

        $this->signature = $signInterface->sign($this->receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    public function cancelSign()
    {
        $signInterface = $this->getSignService(self::CANCEL_SIGN_TYPE);

        $this->signature = $signInterface->sign($this->receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    public function trainingSign()
    {
        $signInterface = $this->getSignService(self::TRAINING_SIGN_TYPE);

        $this->signature = $signInterface->sign($this->receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    public function nullSign()
    {
        $signInterface = $this->getSignService(self::NULL_SIGN_TYPE);

        $this->signature = $signInterface->sign($this->receiptData);
        $this->qr = $this->generateQRCode($this->signature);
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function getQR()
    {
        return $this->qr;
    }
}