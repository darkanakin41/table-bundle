<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class NumberField extends Field
{
    public function __construct($field, $label = null, $id = null)
    {
        parent::__construct($field, $label, $id);
        $this->addClasse('text-right');
        $this->setNumeric(true);
    }
}
