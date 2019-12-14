<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class DateTimeField extends Field
{
    public const EMPTY_VALUE = array(null, '0000-00-00 00:00:00');
    private $format;

    public function __construct($field, $label = null, $jointure = null, $id = null)
    {
        parent::__construct($field, $label, $jointure, $id);
        $this->setFormat("d/m/Y \à H:i");
        $this->setBlock('datetime');
        $this->addClasse('text-right');
    }

    public function getValue($item)
    {
        if (in_array($item, self::EMPTY_VALUE)) {
            return null;
        }
        if ($item instanceof \DateTime && '-0001-11-30' === $item->format('Y-m-d')) {
            return null;
        }

        return parent::getValue($item);
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
