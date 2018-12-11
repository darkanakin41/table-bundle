<?php

namespace PLejeune\TableBundle\Fields;

use PLejeune\TableBundle\Definition\Field;

class CountryField extends Field
{

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("raw");
    }

}