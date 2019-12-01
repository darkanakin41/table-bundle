<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class ImageField extends Field
{
    /**
     * @var bool
     */
    private $displayed;

    public function __construct($field, $label = null, $id = null)
    {
        parent::__construct($field, $label, $id);
        $this->addClasse('text-center');
        $this->setBlock('image');
        $this->setDisplayed(true);
        $this->setFilterable(false);
    }

    /**
     * @deprecated use isDisplayed instead
     *
     * @return bool
     */
    public function getDisplayed()
    {
        return $this->isDisplayed();
    }

    /**
     * @return bool
     */
    public function isDisplayed()
    {
        return $this->displayed;
    }

    /**
     * @param false $displayed
     *
     * @return ImageField
     */
    public function setDisplayed($displayed)
    {
        $this->displayed = $displayed;

        return $this;
    }
}
