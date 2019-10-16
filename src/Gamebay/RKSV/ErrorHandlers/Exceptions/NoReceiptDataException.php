<?php


namespace Gamebay\RKSV\ErrorHandlers\Exceptions;


use Illuminate\Http\Response;

/**
 * Class NoReceiptDataException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class NoReceiptDataException extends RksvException
{
    /**
     * NoReceiptDataException constructor.
     * @param string $message
     * @param int $code
     * @param null $previous
     */
    public function __construct($message = "Tried to instantiate DataReceipt without valid dataset", $code = Response::HTTP_UNPROCESSABLE_ENTITY, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}