<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

class CountField extends NumberField
{
    public function __construct($field, $label = null, $id = null)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock('count');
        $this->setFilterable(false);
        $this->setSortable(false);
    }
}
