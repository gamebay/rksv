<?php

namespace Gamebay\RKSV\ErrorHandlers\Exceptions;

/**
 * Class InvalidItemException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class InvalidItemException extends RksvException
{
    const MESSAGE = "Item must be an array of form ['net'=>X,'tax'=>Y], where 0<=Y<=100";

    /**
     * InvalidItemException constructor.
     * @param string $message
     * @param int $code
     * @param null $previous
     */
    public function __construct(
        string $message = self::MESSAGE,
        $code = 422,
        $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}