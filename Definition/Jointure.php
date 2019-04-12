<?php
namespace PLejeune\TableBundle\Definition;


class Jointure
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $field;
    /**
     * @var string
     */
    private $class;
    /**
     * @var Jointure
     */
    private $parent;

    public function __construct(string $class, string $field, string $id)
    {
        $this->setClass($class);
        $this->setField($field);
        $this->setId($id);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return Jointure
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Jointure $parent
     */
    public function setParent(Jointure $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     * @return Jointure
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @param string $default
     *
     * @return string
     */
    public function getDQL($default = ""){
        if($this->getParent() !== null){
            return $this->getParent()->getId() . "." . $this->getField();
        }
        return $default . "." . $this->getField();
    }

}
