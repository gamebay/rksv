<?php


namespace Gamebay\Rksv\ErrorHandlers\Exceptions;


use Illuminate\Http\Response;
use Throwable;

/**
 * Class InvalidSignTypeException
 * @package Gamebay\Rksv\ErrorHandlers\Exceptions
 */
class InvalidSignTypeException extends \Exception implements \Throwable
{

    /**
     * InvalidSignTypeException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Invalid receipt sign type", $code = Response::HTTP_UNPROCESSABLE_ENTITY, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
