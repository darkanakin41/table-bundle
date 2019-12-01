<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Service;

use Darkanakin41\TableBundle\Definition\AbstractTable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TableService.
 *
 * @deprecated please use "ContainerInterface->get($class)" instead
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
     * Instanciate the given table.
     *
     * @param string $class
     *
     * @return AbstractTable
     *
     * @deprecated please use "ContainerInterface->get($class)" instead
     */
    public function getInstance($class)
    {
        /** @var AbstractTable $table */
        $table = $this->container->get($class);

        return $table;
    }
}
