<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class IntegrationField extends Field
{
    /**
     * @var string
     */
    private $prefix;

    public function __construct($field, $label = null, $id = null)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock('integration');
        $this->setFilterable(false);
        $this->setSortable(false);
    }

    /**
     * @return string
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): IntegrationField
    {
        $this->prefix = $prefix;

        return $this;
    }
}
