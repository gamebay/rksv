<?php


namespace Gamebay\RKSV\ErrorHandlers\Exceptions;


use Illuminate\Http\Response;

/**
 * Class InvalidArgumentsException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class InvalidArgumentsException extends RksvException
{
    /**
     * InvalidArgumentsException constructor.
     * @param $message
     * @param int $code
     * @param null $previous
     */
    public function __construct($message = "", $code = Response::HTTP_UNPROCESSABLE_ENTITY, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}