<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class DateTimeField extends Field
{
    private $format;

    public function __construct($field, $label = null, $jointure = null, $id = null)
    {
        parent::__construct($field, $label, $jointure, $id);
        $this->setFormat("d/m/Y \à H:i");
        $this->setBlock('datetime');
        $this->addClasse('text-right');
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): DateTimeField
    {
        $this->format = $format;

        return $this;
    }
}
