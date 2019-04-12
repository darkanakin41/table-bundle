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
    private $subBlock;
    /**
     * @var string
     */
    private $link;
    /**
     * @var string[]
     */
    private $linkParams;
    /**
     * @var string
     */
    private $linkLabel;
    /**
     * @var array
     */
    private $linkClasses;

    public function __construct($field, $label = NULL, $id = NULL)
    {
        parent::__construct($field, $label, $id);
        $this->setBlock("URL");
        $this->setTarget("");
        $this->setSubBlock("raw");
        $this->setFilterable(false);
        $this->setLinkClasses([]);
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
     *
     * @return URLField
     */
    public function setRoute($route)
    {
        $this->route = $route;
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
     *
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
        return $this->subBlock;
    }

    /**
     * @param mixed $subBlock
     *
     * @return URLField
     */
    public function setSubBlock($subBlock)
    {
        $this->subBlock = $subBlock;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinkLabel()
    {
        return $this->linkLabel;
    }

    /**
     * @param mixed $linkLabel
     *
     * @return URLField
     */
    public function setLinkLabel($linkLabel)
    {
        $this->linkLabel = $linkLabel;
        return $this;
    }

    /**
     * @return array
     */
    public function getLinkClasses(): array
    {
        return $this->linkClasses;
    }

    /**
     * @param array $linkClasses
     */
    public function setLinkClasses(array $linkClasses): void
    {
        $this->linkClasses = $linkClasses;
    }

    /**
     * @param string $linkClass
     */
    public function addLinkClass($linkClass): void
    {
        $this->linkClasses[] = $linkClass;
    }

    public function buildLink($item)
    {
        $converter = new CamelCaseToSnakeCaseNameConverter();

        if (empty($this->getLink())) {
            return null;
        }
        $link = $this->getLink();
        foreach ($this->getLinkParams() as $key => $field) {
            $value = call_user_func(array($item, "get".ucfirst($converter->denormalize($field))));
            $link = str_ireplace($key, $value, $link);
        }

        return $link;
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
     *
     * @return URLField
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getLinkParams(): array
    {
        return $this->linkParams;
    }

    /**
     * @param string[] $linkParams
     *
     * @return URLField
     */
    public function setLinkParams(array $linkParams): URLField
    {
        $this->linkParams = $linkParams;
        return $this;
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

    /**
     * @return mixed
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * @param mixed $routeParams
     *
     * @return URLField
     */
    public function setRouteParams($routeParams)
    {
        $this->routeParams = $routeParams;
        return $this;
    }

}
