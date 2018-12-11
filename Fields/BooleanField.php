<?php

namespace PLejeune\TableBundle\Fields;

use PLejeune\TableBundle\Definition\Field;

class BooleanField extends Field
{

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("boolean");
        $this->addClasse("text-center");
        $this->setChoice(TRUE);
        $this->setValueToLabels([TRUE => "yes", FALSE => "no"]);
        $this->setTranslation(TRUE);
    }
}