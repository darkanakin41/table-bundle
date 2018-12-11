<?php
/**
 * Created by PhpStorm.
 * User: darka
 * Date: 25/07/2018
 * Time: 20:38
 */

namespace PLejeune\TableBundle\Fields;

use PLejeune\TableBundle\Definition\Field;

class UserField extends Field
{

    private $displayed_attributes = array();

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);

        $this->setBlock("user");
        $this->setSortable(FALSE);
        $this->setFilterable(FALSE);

        $this->setDisplayedAttributes(array("firstname"));
    }

    /**
     * @return array
     */
    public function getDisplayedAttributes(): array
    {
        return $this->displayed_attributes;
    }

    /**
     * @param array $displayed_attributes
     * @return UserField
     */
    public function setDisplayedAttributes(array $displayed_attributes): UserField
    {
        $this->displayed_attributes = $displayed_attributes;
        return $this;
    }


}