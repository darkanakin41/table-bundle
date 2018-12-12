<?php
namespace PLejeune\TableBundle\Definition;


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

    /**
     * @return Field
     */
    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * @param Field $field
     * @return Filter
     */
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
     * @return Filter
     */
    public function setValue($value): Filter
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNot(): bool
    {
        return $this->not;
    }

    /**
     * @param bool $not
     * @return Filter
     */
    public function setNot(bool $not): Filter
    {
        $this->not = $not;
        return $this;
    }

    /**
     * @param string $key
     * @param string $alias
     * @return string
     */
    public function getDQL($key, $alias){
        $sign = "=";
        if($this->isNot()){
            $sign = "<>";
        }
        return $this->getField()->getDQL($alias) . " " . $sign . " :" . $this->getAlias($key);
    }

    /**
     * @param string $key
     * @return array
     */
    public function getDQLParameters($key){
        return array(
            $this->getAlias($key) => $this->getValue(),
        );
    }

    /**
     * @param string $key
     * @return string
     */
    public function getAlias($key){
        return $alias_filter = $this->getField()->getId() . "_" . $key;
    }

}