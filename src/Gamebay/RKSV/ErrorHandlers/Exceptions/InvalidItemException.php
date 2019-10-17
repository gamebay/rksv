<?php


namespace Gamebay\RKSV\ErrorHandlers\Exceptions;


use Illuminate\Http\Response;

/**
 * Class InvalidItemException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class InvalidItemException extends RksvException
{
    const MESSAGE = "Item must be an array of form ['net'=>X,'tax'=>Y], where 0<=Y<=100";

    /**
     * InvalidItemException constructor.
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