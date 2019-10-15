<?php


namespace Validators;

use Gamebay\RKSV\ErrorHandlers\Exceptions\InvalidSignTypeException;
use Gamebay\RKSV\Services\ReceiptSigner;
use Illuminate\Http\Response;

/**
 * Class SignatureType
 * @package ErrorHandlers
 */
class SignatureType
{

    /**
     * @var $instanceOf
     */
    private $instanceOf;

    const SIGN_TYPE = [
        //storno code: U1RP
        ReceiptSigner::CANCEL_SIGN_TYPE => 'Cancel',
        //training code: VFJB
        ReceiptSigner::TRAINING_SIGN_TYPE => 'Training',
        ReceiptSigner::NORMAL_SIGN_TYPE => 'Normal',
        //null code: 0
        ReceiptSigner::NULL_SIGN_TYPE => 'Null',
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