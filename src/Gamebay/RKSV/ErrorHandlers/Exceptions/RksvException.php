<?php


namespace Gamebay\RKSV\ErrorHandlers\Exceptions;


/**
 * Class RksvException
 * @package Gamebay\RKSV\ErrorHandlers\Exceptions
 */
class RksvException extends \Exception
{
    /**
     * RksvException constructor.
     * @param $message
     * @param int $code
     * @param null $previous
     */
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->code . ": " . __CLASS__ . ": " . $this->message . "\n";
    }
}