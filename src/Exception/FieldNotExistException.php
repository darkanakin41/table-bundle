<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Exception;

use Throwable;

class FieldNotExistException extends \Exception
{
    public function __construct($field, int $code = 0, Throwable $previous = null)
    {
        $message = "Field $field does not exist in given table";
        parent::__construct($message, $code, $previous);
    }
}
