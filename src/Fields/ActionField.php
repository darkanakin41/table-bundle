<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Fields;

use Darkanakin41\TableBundle\Definition\Field;
use Exception;
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

    public function __construct($field, $label = null, $id = null)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock('action');
        $this->setAttributes(array('data-value' => sprintf('{%s}', $field)));
        $this->setButtonLabel('');
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
     */
    public function setAttributes(array $attributes): ActionField
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $value
     */
    public function addAttribute($attribute, $value): void
    {
        $this->attributes[$attribute] = $value;
    }

    public function getButtonLabel(): string
    {
        return $this->buttonLabel;
    }

    public function setButtonLabel(string $buttonLabel): ActionField
    {
        $this->buttonLabel = $buttonLabel;

        return $this;
    }

    /**
     * Retrieve the attribute value from the item.
     *
     * @param $attribute
     * @param $item
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function buildAttribute($attribute, $item)
    {
        if (!isset($this->attributes[$attribute])) {
            throw new Exception('Attribute not found');
        }

        $converter = new CamelCaseToSnakeCaseNameConverter();

        $value = call_user_func(array($item, 'get'.ucfirst($converter->denormalize($this->getField()))));

        return str_ireplace('{'.$this->getField().'}', $value, $this->attributes[$attribute]);
    }
}
