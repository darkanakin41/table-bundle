<?php

namespace PLejeune\TableBundle\Definition;


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
     * @var Jointure
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
     * @var boolean
     */
    private $numeric;

    /**
     * @var string
     */
    private $value_to_label;

    /**
     * @var boolean
     */
    private $translation;
    /**
     * @var string
     */
    private $translation_prefix;
    /**
     * @var AbstractTable
     */
    private $table;

    public function __construct($field, $label = NULL, $id = NULL)
    {
        $this->setField($field);
        $this->setId(is_null($id) ? $this->getField() : $id);
        $this->setLabel(is_null($label) ? $this->getField() : $label);
        $this->setBlock("raw");
        $this->setFilterable(TRUE);
        $this->setChoice(FALSE);
        $this->setSortable(TRUE);
        $this->setVisible(TRUE);
        $this->setClasses(array());
        $this->setNumeric(FALSE);
        $this->setTranslation(FALSE);
    }

    /**
     * @return AbstractTable
     */
    public function getTable(): AbstractTable
    {
        return $this->table;
    }

    /**
     * @param AbstractTable $table
     * @return Field
     */
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
     * @return self
     */
    public function setValueToLabels($labels)
    {
        $this->value_to_label = $labels;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTranslation(): bool
    {
        return $this->translation;
    }

    /**
     * @param bool $translation
     * @return Field
     */
    public function setTranslation(bool $translation): Field
    {
        $this->translation = $translation;
        return $this;
    }

    /**
     * @param $key
     * @return string
     */
    public function getValueToLabel($key)
    {
        return isset($this->value_to_label[$key]) ? $this->value_to_label[$key] : "unknown_value";
    }

    /**
     * @return string
     */
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

    /**
     * @param string $translation_prefix
     * @return Field
     */
    public function setTranslationPrefix(string $translation_prefix): Field
    {
        if (!empty($translation_prefix)) {
            $this->setTranslation(TRUE);
        }
        $this->translation_prefix = $translation_prefix;
        return $this;
    }

    /**
     * @param string $id
     * @return Field
     */
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

    /**
     * @param string $label
     * @return Field
     */
    public function setLabel(string $label): Field
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     * @return Field
     */
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
     * @return Field
     */
    public function setJointure(?Jointure $jointure): Field
    {
        if (!is_null($jointure)) {
            $this->jointure = $jointure;
            $this->setSortable(FALSE);
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

    /**
     * @return bool
     */
    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    /**
     * @param bool $filterable
     * @return Field
     */
    public function setFilterable(bool $filterable): Field
    {
        $this->filterable = $filterable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isChoice(): bool
    {
        return $this->choice;
    }

    /**
     * @param bool $choice
     * @return Field
     */
    public function setChoice(bool $choice): Field
    {
        $this->choice = $choice;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     * @return Field
     */
    public function setSortable(bool $sortable): Field
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return Field
     */
    public function setVisible(bool $visible): Field
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
        return $this->numeric;
    }

    /**
     * @param bool $numeric
     * @return Field
     */
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
     * @return Field
     */
    public function setClasses(array $classes): Field
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * @param string $classe
     */
    public function addClasse(string $classe)
    {
        $this->classes[] = $classe;
    }

    /**
     * Generate DQL
     * @param string $default
     * @return string
     */
    public function getDQL($default = "")
    {
        if (!is_null($this->getJointure())) {
            return strtolower($this->getJointure()->getId()) . "." . $this->getField();
        }
        return $default . "." . $this->getField();
    }

    /**
     * @param AbstractTable $table
     * @return string
     */
    public function getQBFilter(AbstractTable $table)
    {
        if ($this->isChoice()) {
            return $this->getDQL($table->getAlias()) . ' = :' . $this->getId();
        }

        return $this->getDQL($table->getAlias()) . ' LIKE :' . $this->getId();
    }

    public function getValue($item)
    {
        return $item;
    }

    /**
     * Set the block of the field to display
     * @param string $block
     * @return Field
     */
    protected function setBlock(string $block): Field
    {
        $this->block = $block;
        return $this;
    }
}