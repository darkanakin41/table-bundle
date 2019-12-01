<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

class StarField extends NumberField
{
    public function __construct($field, $label = null, $id = null)
    {
        parent::__construct($field, $label, $id);
        $this->addClasse('star');
        $this->setBlock('star');
    }
}
