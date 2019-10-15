<?php


namespace Gamebay\RKSV\ErrorHandlers\Exceptions;


use Illuminate\Http\Response;

/**
 * Class InvalidItemException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class InvalidItemException extends RksvException
{

    /**
     * InvalidItemException constructor.
     * @param $message
     * @param int $code
     * @param null $previous
     */
    public function __construct($message = "Invalid item array constructed. ", $code = Response::HTTP_UNPROCESSABLE_ENTITY, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}