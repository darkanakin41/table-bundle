<?php

namespace PLejeune\TableBundle\Fields;

use PLejeune\TableBundle\Definition\Field;

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

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("array");
        $this->setSubBlock("raw");
        $this->setSeparator(",");
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
     *
     * @return ArrayField
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
     *
     * @return ArrayField
     */
    public function setSubBlock(?string $sub_block): ArrayField
    {
        $this->sub_block = $sub_block;
        return $this;
    }


}
