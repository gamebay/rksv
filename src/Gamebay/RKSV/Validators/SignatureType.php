<?php

namespace Gamebay\RKSV\Validators;

use Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException;
use Gamebay\RKSV\Services\ReceiptSigner;

/**
 * Class SignatureType
 * @package ErrorHandlers
 */
class SignatureType
{

    /**
     * @var string $instanceOf
     */
    private string $instanceOf;

    /** @var array containing key values for constructing valid classes */
    const SIGN_TYPE = [
        ReceiptSigner::CANCEL_SIGN_TYPE => 'Cancel',
        ReceiptSigner::TRAINING_SIGN_TYPE => 'Training',
        ReceiptSigner::NORMAL_SIGN_TYPE => 'Normal',
        ReceiptSigner::NULL_SIGN_TYPE => 'Null',
    ];

    /** @var array containing values for generating receipts which aren't normally chained */
    const SIGN_CODE = [
        //storno
        ReceiptSigner::CANCEL_SIGN_TYPE => 'U1RP',  // base64 encoding of the word STO
        //training
        ReceiptSigner::TRAINING_SIGN_TYPE => 'VFJB',  //base64 encoding of the word TRA
        //first receipt
        ReceiptSigner::NULL_SIGN_TYPE => '0',
    ];

    /**
     * SignatureType constructor.
     * @param string $type
     * @throws InvalidSignTypeException
     */
    public function __construct(string $type)
    {
        if (!array_key_exists($type, self::SIGN_TYPE)) {
            throw new InvalidSignTypeException();
        }

        $this->setInstanceOf(self::SIGN_TYPE[$type]);

        return $this;
    }

    /**
     * @param string $type
     */
    protected function setInstanceOf(string $type)
    {
        $this->instanceOf = $type;
    }

    /**
     * @return string
     */
    public function getInstanceOf(): string
    {
        return $this->instanceOf;
    }
}