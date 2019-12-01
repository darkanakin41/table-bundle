<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class ArrayField extends Field
{
    /**
     * @var string
     */
    private $separator;
    /**
     * @var string
     */
    private $sub_block;

    public function __construct($field, $label = null, $id = null)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock('array');
        $this->setSubBlock('raw');
        $this->setSeparator(',');
    }

    /**
     * @return string
     */
    public function getSeparator(): ?string
    {
        return $this->separator;
    }

    /**
     * @param string $separator
     */
    public function setSeparator(?string $separator): ArrayField
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubBlock(): ?string
    {
        return $this->sub_block;
    }

    /**
     * @param string $sub_block
     */
    public function setSubBlock(?string $sub_block): ArrayField
    {
        $this->sub_block = $sub_block;

        return $this;
    }
}
