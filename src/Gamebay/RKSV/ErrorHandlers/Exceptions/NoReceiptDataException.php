<?php


namespace Gamebay\RKSV\ErrorHandlers\Exceptions;


use Illuminate\Http\Response;

/**
 * Class NoReceiptDataException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class NoReceiptDataException extends RksvException
{
    const MESSAGE = "ReceiptData must be provided.";

    /**
     * NoReceiptDataException constructor.
     * @param $message
     * @param int $code
     * @param null $previous
     */
    public function __construct($message = self::MESSAGE,
                                $code = Response::HTTP_UNPROCESSABLE_ENTITY,
                                $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}