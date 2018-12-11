<?php

namespace PLejeune\TableBundle\Definition;


class Action
{
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $route;
    /**
     * @var string[]
     */
    private $routeParam;
    /**
     * @var boolean
     */
    private $translate;

    /**
     * Action constructor.
     */
    public function __construct($label, $route, $routeParam = [])
    {
        $this->setLabel($label);
        $this->setRoute($route);
        $this->setRouteParam($routeParam);
        $this->setTranslate(TRUE);
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Action
     */
    public function setLabel(string $label): Action
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return Action
     */
    public function setRoute(string $route): Action
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRouteParam(): array
    {
        return $this->routeParam;
    }

    /**
     * @param string[] $routeParam
     * @return Action
     */
    public function setRouteParam(array $routeParam): Action
    {
        $this->routeParam = $routeParam;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTranslate(): bool
    {
        return $this->translate;
    }

    /**
     * @param bool $translate
     * @return Action
     */
    public function setTranslate(bool $translate): Action
    {
        $this->translate = $translate;
        return $this;
    }


}