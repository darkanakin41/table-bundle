<?php

namespace PLejeune\TableBundle\Fields;


use PLejeune\TableBundle\Definition\Field;

class ImageField extends Field
{
    /**
     * @var false
     */
    private $displayed;

    public function __construct($field, $label = NULL, $id = NULL)
{
    parent::__construct($field, $label, $id);
    $this->addClasse("text-center");
    $this->setBlock("image");
    $this->setDisplayed(TRUE);
    $this->setFilterable(FALSE);
}

    /**
     * @return false
     */
    public function getDisplayed()
{
    return $this->displayed;
}

    /**
     * @param false $displayed
     * @return ImageField
     */
    public function setDisplayed($displayed)
{
    $this->displayed = $displayed;
    return $this;
}


}