<?php

namespace PLejeune\TableBundle\Service;

use PLejeune\TableBundle\Definition\AbstractTable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TableService
 * @deprecated please use "ContainerInterface->get($class)" instead
 * @package PLejeune\TableBundle\Service
 */
class TableService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Instanciate the given table
     *
     * @param string $class
     *
     * @return AbstractTable
     * @deprecated please use "ContainerInterface->get($class)" instead
     */
    public function getInstance($class)
    {
        /** @var AbstractTable $table */
        $table = $this->container->get($class);
        return $table;
    }
}
