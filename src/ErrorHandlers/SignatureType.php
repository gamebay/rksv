<?php


namespace ErrorHandlers;


use ErrorHandlers\Exceptions\InvalidSignTypeException;
use Illuminate\Http\Response;
use phpDocumentor\Reflection\Types\String_;
use Services\ReceiptSigner;

/**
 * Class SignatureType
 * @package ErrorHandlers
 */
class SignatureType
{

    /**
     * @var
     */
    private $instanceOf;

    /**
     *
     */
    const SIGN_TYPE = [
        //storno code: U1RP
        ReceiptSigner::CANCEL_SIGN_TYPE => 'Cancel',
        //training code: VFJB
        ReceiptSigner::TRAINING_SIGN_TYPE => 'Training',
        ReceiptSigner::NORMAL_SIGN_TYPE => 'Normal',
        //null code: cashbox ID
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
            throw new InvalidSignTypeException('Invalid receipt sign type', Response::HTTP_UNPROCESSABLE_ENTITY);
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
     * @return mixed
     */
    public function getInstanceOf(): string
    {
        return $this->instanceOf;
    }

}