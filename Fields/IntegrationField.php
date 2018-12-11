<?php
namespace PLejeune\TableBundle\Fields;


use PLejeune\TableBundle\Definition\Field;

class IntegrationField extends Field
{

    /**
     * @var string
     */
    private $prefix;

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("integration");
        $this->setFilterable(FALSE);
        $this->setSortable(FALSE);
    }

    /**
     * @return string
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return IntegrationField
     */
    public function setPrefix(string $prefix): IntegrationField
    {
        $this->prefix = $prefix;
        return $this;
    }


}