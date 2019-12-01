<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Definition;

class Filter
{
    /**
     * @var Field
     */
    private $field;
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var bool
     */
    private $not;

    /**
     * Filter constructor.
     */
    public function __construct(Field $field, $value, $not = false)
    {
        $this->setField($field);
        $this->setValue($value);
        $this->setNot($not);
    }

    public function getField(): Field
    {
        return $this->field;
    }

    public function setField(Field $field): Filter
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     */
    public function setValue($value): Filter
    {
        $this->value = $value;

        return $this;
    }

    public function isNot(): bool
    {
        return $this->not;
    }

    public function setNot(bool $not): Filter
    {
        $this->not = $not;

        return $this;
    }

    /**
     * @param string $key
     * @param string $alias
     *
     * @return string
     */
    public function getDQL($key, $alias)
    {
        $sign = '=';
        if ($this->isNot()) {
            $sign = '<>';
        }

        return $this->getField()->getDQL($alias).' '.$sign.' :'.$this->getAlias($key);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getDQLParameters($key)
    {
        return array(
            $this->getAlias($key) => $this->getValue(),
        );
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getAlias($key)
    {
        return $alias_filter = $this->getField()->getId().'_'.$key;
    }
}
