<?php

namespace Darkanakin41\TableBundle\Exception;

use Exception;

class UnknownBlockException extends Exception
{
    public function __construct($block)
    {
        $message = "Block $block does not exist in given template";
        parent::__construct($message);
    }

}
