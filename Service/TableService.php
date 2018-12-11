<?php

namespace PLejeune\TableBundle\Service;

use PLejeune\TableBundle\Definition\AbstractTable;
use PLejeune\TableBundle\Definition\Field;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TableService extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('table_render', array($this, 'render'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('table_render_field', array($this, 'renderField'), array('is_safe' => array('html'))),
        );
    }

    public function getName()
    {
        return 'TableService';
    }

    public function render(AbstractTable $table)
    {
        return $table->render();
    }

    public function renderField(AbstractTable $table, Field $field, $item)
    {
        foreach (array_reverse($table->getTemplateFields()) as $template) {
            $template = $this->container->get("twig")->load($template);
            if (!$template->hasBlock($field->getBlock())) continue;
            return $template->renderBlock($field->getBlock(), array('table' => $table, 'field' => $field, 'item' => $item));
        }
        return "";
    }

    /**
     * @param $class
     * @return AbstractTable
     */
    public function getInstance($class)
    {
        $table = new $class(
            $this->container->get("knp_paginator"),
            $this->container->get("request_stack"),
            $this->container->get("doctrine"),
            $this->container->get("twig"),
            $this->container->get("session"),
            $this->container->get("form.factory")
        );
        return $table;
    }
}
