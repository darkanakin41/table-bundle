<?php

namespace Darkanakin41\TableBundle\Fields;

use Exception;
use Darkanakin41\TableBundle\Definition\Field;
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
        $this->setButtonLabel("");
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
     * @param string $value
     * @return void
     */
    public function addAttribute($attribute, $value): void
    {
        $this->attributes[$attribute] = $value;
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

    /**
     * Retrieve the attribute value from the item
     *
     * @param $attribute
     * @param $item
     *
     * @return mixed
     * @throws Exception
     */
    public function buildAttribute($attribute, $item){
        if(!isset($this->attributes[$attribute])){
            throw new Exception("Attribute not found");
        }
        $converter = new CamelCaseToSnakeCaseNameConverter();

        $value = call_user_func([$item, "get" . ucfirst($converter->denormalize($this->getField()))]);
        return str_ireplace('{' . $this->getField() . '}', $value, $this->attributes[$attribute]);
    }

}
