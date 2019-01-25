<?php

namespace PLejeune\TableBundle\Fields;

use PLejeune\TableBundle\Definition\Field;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class ActionField extends Field
{
    /**
     * @var string[]
     */
    private $attributes;
    /**
     * @var string
     */
    private $button_label;

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("action");
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string[] $attributes
     * @return ActionField
     */
    public function setAttributes(array $attributes): ActionField
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return string
     */
    public function getButtonLabel(): string
    {
        return $this->button_label;
    }

    /**
     * @param string $button_label
     * @return ActionField
     */
    public function setButtonLabel(string $button_label): ActionField
    {
        $this->button_label = $button_label;
        return $this;
    }

    public function buildAttribute($attribute, $item){
        if(!isset($this->attributes[$attribute])) throw new \Exception("Attribute not found");
        $converter = new CamelCaseToSnakeCaseNameConverter();

        $value = call_user_func(array($item, "get" . $converter->denormalize($this->getField())));
        return str_ireplace('{' . $this->getField() . '}', $value, $this->attributes[$attribute]);
    }

}