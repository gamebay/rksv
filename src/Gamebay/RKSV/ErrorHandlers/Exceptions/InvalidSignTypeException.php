<?php

namespace Gamebay\RKSV\ErrorHandlers\Exceptions;

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
    public function __construct(
        $message = "Invalid receipt sign type given",
        $code = 422,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}