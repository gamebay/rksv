<?php


namespace Gamebay\RKSV\ErrorHandlers\Exceptions;


use Illuminate\Http\Response;
use Throwable;

/**
 * Class InvalidSignTypeException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class InvalidSignTypeException extends RksvException
{

    /**
     * InvalidSignTypeException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Invalid receipt sign type given", $code = Response::HTTP_UNPROCESSABLE_ENTITY, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
