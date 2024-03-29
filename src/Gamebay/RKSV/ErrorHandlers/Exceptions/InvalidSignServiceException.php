<?php

namespace Gamebay\RKSV\ErrorHandlers\Exceptions;

use Throwable;

/**
 * Class InvalidSignServiceException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class InvalidSignServiceException extends RksvException
{
    /**
     * InvalidSignServiceException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = "Tried to instantiate invalid SignService class",
        $code = 422,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}