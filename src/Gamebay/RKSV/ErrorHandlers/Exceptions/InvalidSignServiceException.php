<?php


namespace Gamebay\RKSV\ErrorHandlers\Exceptions;


use Illuminate\Http\Response;
use Throwable;

/**
 * Class InvalidSignServiceException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class InvalidSignServiceException extends RksvException implements \Throwable
{

    /**
     * InvalidSignServiceException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Tried to instantiate invalid SignService class", $code = Response::HTTP_NOT_ACCEPTABLE, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
