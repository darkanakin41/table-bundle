<?php

namespace Darkanakin41\TableBundle\Fields;

class StarField extends NumberField
{
    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->addClasse("star");
        $this->setBlock("star");
    }

}
