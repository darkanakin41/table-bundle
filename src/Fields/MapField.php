<?php

namespace Darkanakin41\TableBundle\Fields;

class MapField extends ArrayField
{

    /**
     * @var string[]
     */
    private $key_values;

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("map");
        $this->setFilterable(false);
        $this->setSortable(false);
    }

    /**
     * @param mixed $item
     *
     * @return mixed|null
     */
    public function getValue($item)
    {
        if (!is_array($item)) {
            return parent::getValue($item);
        }
        foreach ($this->getKeyValues() as $key) {
            if (isset($item[$key])) {
                return $item[$key];
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getKeyValues(): array
    {
        return $this->key_values;
    }

    /**
     * @param array $key_values
     */
    public function setKeyValues(array $key_values): void
    {
        $this->key_values = $key_values;
    }
}
