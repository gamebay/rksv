<?php

namespace Gamebay\RKSV\ErrorHandlers\Exceptions;

/**
 * Class NoReceiptDataException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class NoReceiptDataException extends RksvException
{
    const MESSAGE = "ReceiptData must be provided.";

    /**
     * NoReceiptDataException constructor.
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