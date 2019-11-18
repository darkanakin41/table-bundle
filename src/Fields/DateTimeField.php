<?php
namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;

class DateTimeField extends Field
{
    private $format;

    public function __construct($field, $label = NULL, $jointure = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $jointure, $id);
        $this->setFormat("d/m/Y \à H:i");
        $this->setBlock("datetime");
        $this->addClasse("text-right");
    }


    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return DateTimeField
     */
    public function setFormat(string $format): DateTimeField
    {
        $this->format = $format;
        return $this;
    }


}
