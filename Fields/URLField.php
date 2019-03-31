<?php

namespace PLejeune\TableBundle\Fields;

use PLejeune\TableBundle\Definition\Field;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class URLField extends Field
{
    /**
     * @var string
     */
    private $route;
    /**
     * @var string[]
     */
    private $routeParams;
    /**
     * @var string
     */
    private $target;
    /**
     * @var string
     */
    private $sub_block;
    /**
     * @var string
     */
    private $link;
    /**
     * @var string[]
     */
    private $link_params;
    /**
     * @var string
     */
    private $link_label;

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("URL");
        $this->setTarget("");
        $this->setSubBlock("raw");
        $this->setFilterable(false);
    }


    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     * @return URLField
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * @param mixed $routeParams
     * @return URLField
     */
    public function setRouteParams($routeParams)
    {
        $this->routeParams = $routeParams;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return URLField
     */
    public function setTarget(string $target): URLField
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubBlock()
    {
        return $this->sub_block;
    }

    /**
     * @param mixed $sub_block
     * @return URLField
     */
    public function setSubBlock($sub_block)
    {
        $this->sub_block = $sub_block;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     * @return URLField
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinkLabel()
    {
        return $this->link_label;
    }

    /**
     * @param mixed $link_label
     * @return URLField
     */
    public function setLinkLabel($link_label)
    {
        $this->link_label = $link_label;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getLinkParams(): array
    {
        return $this->link_params;
    }

    /**
     * @param string[] $link_params
     * @return URLField
     */
    public function setLinkParams(array $link_params): URLField
    {
        $this->link_params = $link_params;
        return $this;
    }

    public function buildLink($item){
        $converter = new CamelCaseToSnakeCaseNameConverter();

        if(empty($this->getLink())){
            return null;
        }
        $link = $this->getLink();
        foreach($this->getLinkParams() as $key => $field){
            $value = call_user_func(array($item, "get" . $converter->denormalize($field)));
            $link = str_ireplace($key, $value, $link);
        }

        return $link;
    }


    public function getCalculatedParams($item)
    {
        $return = array();
        foreach ($this->getRouteParams() as $key => $fieldname) {
            $field = $this->getTable()->getField($fieldname);
            $value = $this->getTable()->getValue($item, $field);
            $return[$key] = $value;
        }

        return $return;
    }

}
