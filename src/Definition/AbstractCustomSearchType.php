<?php


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
     * Get the id of the custom search type (uniq)
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Create the form item
     *
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    abstract function buildFormItem(FormBuilderInterface $builder);

    /**
     * Apply the filter to the query builder
     *
     * @param FormInterface $form
     * @param QueryBuilder  $qb
     *
     * @return void
     */
    abstract function applyToQueryBuilder(FormInterface $form, QueryBuilder $qb);
}
