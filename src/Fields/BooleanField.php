<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class BooleanField extends Field
{
    public function __construct($field, $label = null, $id = null)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock('boolean');
        $this->addClasse('text-center');
        $this->setChoice(true);
        $this->setValueToLabels(array(true => 'yes', false => 'no'));
        $this->setTranslation(true);
    }
}
