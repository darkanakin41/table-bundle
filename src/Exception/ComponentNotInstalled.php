<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Exception;

use Throwable;

class ComponentNotInstalled extends \Exception
{
    public function __construct($componentName, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('The component %s is not installed, please use `composer require %s` and then try again', $componentName, $componentName);
        parent::__construct($message, $code, $previous);
    }
}
