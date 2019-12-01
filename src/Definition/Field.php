<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Definition;

class Field
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $field;
    /**
     * @var Jointure|null
     */
    private $jointure;
    /**
     * @var string
     */
    private $block;
    /**
     * @var bool
     */
    private $filterable;
    /**
     * @var bool
     */
    private $sortable;
    /**
     * @var bool
     */
    private $choice;
    /**
     * @var bool
     */
    private $visible;

    /**
     * @var string[]
     */
    private $classes;

    /**
     * @var bool
     */
    private $numeric;

    /**
     * @var array
     */
    private $value_to_label;

    /**
     * @var bool
     */
    private $translation;
    /**
     * @var string
     */
    private $translation_prefix;
    /**
     * @var AbstractTable|null
     */
    private $table;

    public function __construct($field, $label = null, $id = null)
    {
        $this->setField($field);
        $this->setId(is_null($id) ? $this->getField() : $id);
        $this->setLabel(is_null($label) ? $this->getField() : $label);
        $this->setBlock('raw');
        $this->setFilterable(true);
        $this->setChoice(false);
        $this->setSortable(true);
        $this->setVisible(true);
        $this->setClasses(array());
        $this->setNumeric(false);
        $this->setTranslation(false);
    }

    /**
     * @return AbstractTable
     */
    public function getTable(): ?AbstractTable
    {
        return $this->table;
    }

    public function setTable(AbstractTable $table): Field
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValueToLabels()
    {
        return $this->value_to_label;
    }

    /**
     * @param mixed $labels
     *
     * @return self
     */
    public function setValueToLabels(array $labels)
    {
        $this->value_to_label = array_change_key_case($labels, CASE_LOWER);

        return $this;
    }

    public function isTranslation(): bool
    {
        return $this->translation;
    }

    public function setTranslation(bool $translation): Field
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getValueToLabel($key)
    {
        if (null === $key) {
            return '#N/C';
        }

        return isset($this->value_to_label[$key]) ? $this->value_to_label[$key] : 'unknown_value';
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTranslationPrefix(): ?string
    {
        return $this->translation_prefix;
    }

    public function setTranslationPrefix(string $translation_prefix): Field
    {
        if (!empty($translation_prefix)) {
            $this->setTranslation(true);
        }
        $this->translation_prefix = $translation_prefix;

        return $this;
    }

    public function setId(string $id): Field
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): Field
    {
        $this->label = $label;

        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): Field
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return Jointure
     */
    public function getJointure(): ?Jointure
    {
        return $this->jointure;
    }

    /**
     * @param Jointure $jointure
     */
    public function setJointure(?Jointure $jointure): Field
    {
        if (!is_null($jointure)) {
            $this->jointure = $jointure;
            $this->setSortable(false);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBlock(): ?string
    {
        return $this->block;
    }

    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    public function setFilterable(bool $filterable): Field
    {
        $this->filterable = $filterable;

        return $this;
    }

    public function isChoice(): bool
    {
        return $this->choice;
    }

    public function setChoice(bool $choice): Field
    {
        $this->choice = $choice;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function setSortable(bool $sortable): Field
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): Field
    {
        $this->visible = $visible;

        return $this;
    }

    public function isNumeric(): bool
    {
        return $this->numeric;
    }

    public function setNumeric(bool $numeric): Field
    {
        $this->numeric = $numeric;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @param string[] $classes
     */
    public function setClasses(array $classes): Field
    {
        $this->classes = $classes;

        return $this;
    }

    public function addClasse(string $classe)
    {
        $this->classes[] = $classe;
    }

    /**
     * Generate DQL.
     *
     * @param string $default
     *
     * @return string
     */
    public function getDQL($default = '')
    {
        if (!is_null($this->getJointure())) {
            return strtolower($this->getJointure()->getId()).'.'.$this->getField();
        }

        return $default.'.'.$this->getField();
    }

    /**
     * @return string
     */
    public function getQBFilter(AbstractTable $table)
    {
        if ($this->isChoice()) {
            return $this->getDQL($table->getAlias()).' = :'.$this->getId();
        }

        return $this->getDQL($table->getAlias()).' LIKE :'.$this->getId();
    }

    public function getValue($item)
    {
        return $item;
    }

    /**
     * Set the block of the field to display.
     */
    protected function setBlock(string $block): Field
    {
        $this->block = $block;

        return $this;
    }
}
