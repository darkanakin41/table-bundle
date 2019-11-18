<?php

namespace Darkanakin41\TableBundle\Fields;


use Darkanakin41\TableBundle\Definition\Field;

class ImageField extends Field
{
    /**
     * @var boolean
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
     * @deprecated use isDisplayed instead
     * @return boolean
     */
    public function getDisplayed()
    {
        return $this->isDisplayed();
    }

    /**
     * @return boolean
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
