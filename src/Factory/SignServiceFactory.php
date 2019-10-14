<?php


namespace Factory;

use ErrorHandlers\SignatureType;

class SignServiceFactory
{

    public static function create(string $type)
    {

        $typeSignClass = SignatureType($type);

        if (class_exists($typeSignClass)) {
            return new $typeSignClass
        }
    }

}