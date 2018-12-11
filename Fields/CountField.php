<?php
namespace PLejeune\TableBundle\Fields;

class CountField extends NumberField
{

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("count");
        $this->setFilterable(FALSE);
        $this->setSortable(FALSE);
    }
}