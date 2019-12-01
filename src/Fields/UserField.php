<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class UserField extends Field
{
    private $displayed_attributes = array();

    public function __construct($field, $label = null, $id = null)
    {
        parent::__construct($field, $label, $id);

        $this->setBlock('user');
        $this->setSortable(false);
        $this->setFilterable(false);

        $this->setDisplayedAttributes(array('firstname'));
    }

    public function getDisplayedAttributes(): array
    {
        return $this->displayed_attributes;
    }

    public function setDisplayedAttributes(array $displayed_attributes): UserField
    {
        $this->displayed_attributes = $displayed_attributes;

        return $this;
    }
}
