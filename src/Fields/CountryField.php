<?php

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class CountryField extends Field
{

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("raw");
    }

}