<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Definition;

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
     * @var bool
     */
    private $translate;

    /**
     * Action constructor.
     */
    public function __construct($label, $route, $routeParam = array())
    {
        $this->setLabel($label);
        $this->setRoute($route);
        $this->setRouteParam($routeParam);
        $this->setTranslate(true);
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): Action
    {
        $this->label = $label;

        return $this;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

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
     */
    public function setRouteParam(array $routeParam): Action
    {
        $this->routeParam = $routeParam;

        return $this;
    }

    public function isTranslate(): bool
    {
        return $this->translate;
    }

    public function setTranslate(bool $translate): Action
    {
        $this->translate = $translate;

        return $this;
    }
}
