<?php


namespace Gamebay\RKSV\ErrorHandlers\Exceptions;


class RksvException extends \Exception
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return $this->code . ": " . __CLASS__ . ": " . $this->message . "\n";
    }
}