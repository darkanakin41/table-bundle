<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Definition;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

abstract class AbstractCustomSearchType
{
    /** @var string */
    private $id;

    /**
     * AbstractCustomSearchType constructor.
     *
     * @param string $id the uniq identifier of the search type
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Get the id of the custom search type (uniq).
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Create the form item.
     *
     * @return void
     */
    abstract public function buildFormItem(FormBuilderInterface $builder);

    /**
     * Apply the filter to the query builder.
     *
     * @return void
     */
    abstract public function applyToQueryBuilder(FormInterface $form, QueryBuilder $qb);
}
