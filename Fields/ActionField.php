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
    private $buttonLabel;

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("action");
        $this->setAttributes([]);
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
     * @param string $attribute
     *
     * @return ActionField
     */
    public function addAttribute($attribute): ActionField
    {
        $this->attributes[] = $attribute;
        return $this;
    }

    /**
     * @return string
     */
    public function getButtonLabel(): string
    {
        return $this->buttonLabel;
    }

    /**
     * @param string $buttonLabel
     *
     * @return ActionField
     */
    public function setButtonLabel(string $buttonLabel): ActionField
    {
        $this->buttonLabel = $buttonLabel;
        return $this;
    }

    public function buildAttribute($attribute, $item){
        if(!isset($this->attributes[$attribute])) throw new \Exception("Attribute not found");
        $converter = new CamelCaseToSnakeCaseNameConverter();

        $value = call_user_func(array($item, "get" . $converter->denormalize($this->getField())));
        return str_ireplace('{' . $this->getField() . '}', $value, $this->attributes[$attribute]);
    }

}
