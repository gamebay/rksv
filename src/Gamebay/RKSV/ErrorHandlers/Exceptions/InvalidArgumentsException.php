<?php

namespace Gamebay\RKSV\ErrorHandlers\Exceptions;

/**
 * Class InvalidArgumentsException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class InvalidArgumentsException extends RksvException
{
    /**
     * InvalidArgumentsException constructor.
     * @param string $message
     * @param int $code
     * @param null $previous
     */
    public function __construct(
        string $message = "",
        $code = 422,
        $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}