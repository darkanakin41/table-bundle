<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Definition;

use Darkanakin41\TableBundle\Exception\FieldNotExistException;
use Darkanakin41\TableBundle\Form\SearchForm;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig_Environment;

abstract class AbstractTable
{
    const FIELDS_DISPLAYED = 'fields_displayed';

    /**
     * @var Field[]
     */
    private $fields = array();

    /**
     * @var string[]
     */
    private $fields_displayed = array();

    /**
     * @var string[]
     */
    private $fields_displayed_default = array();

    /**
     * @var Jointure[]
     */
    private $jointures = array();

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var PaginatorInterface
     */
    private $paginator_calculated;

    /**
     * @var RequestStack
     */
    private $request_stack;

    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string[]
     */
    private $sort = array();

    /**
     * @var Filter[]
     */
    private $filters = array();

    /**
     * @var Form
     */
    private $search_form;

    /**
     * @var bool
     */
    private $display_column_selector;

    /**
     * @var bool
     */
    private $display_pagination;

    /**
     * @var bool
     */
    private $display_menu;

    /**
     * @var bool
     */
    private $display_total_items;
    /**
     * @var bool
     */
    private $display_menu_label;

    /**
     * @var string
     */
    private $template;
    /**
     * @var string
     */
    private $templateFields;
    /**
     * @var string[]
     */
    private $table_classes = array();
    /**
     * @var Action[]
     */
    private $actions = array();

    public function __construct(PaginatorInterface $paginator, RequestStack $request_stack, RegistryInterface $doctrine, Twig_Environment $twigEnvironment, SessionInterface $session, FormFactoryInterface $formFactory, ContainerInterface $container)
    {
        $this->paginator = $paginator;
        $this->request_stack = $request_stack;
        $this->setDoctrine($doctrine);
        $this->twig = $twigEnvironment;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->fields = array();
        $this->jointures = array();
        $this->filters = array();
        $this->sort = array();

        $this->setConfig($container->getParameter('darkanakin41.table.config'));

        $this->setTableClasses(array());
        $this->setActions(array());

        $this->setDisplayMenu(true);
        $this->setDisplayColumnSelector(true);
        $this->setDisplayPagination(true);
        $this->setDisplayTotalItems(true);
        $this->setDisplayMenuLabel(false);

        $this->setLimit(10);
        $this->__init__();

        if (empty($this->getSessionAttribute(self::FIELDS_DISPLAYED))) {
            $this->setSessionAttribute(self::FIELDS_DISPLAYED, $this->getFieldsDisplayedDefault());
        }

        $this->setFieldsDisplayed($this->getSessionAttribute(self::FIELDS_DISPLAYED));
    }

    /**
     * Intialise all fields of the table and so on.
     */
    abstract protected function __init__();

    /**
     * @return string[]
     */
    public function getFieldsDisplayedDefault(): array
    {
        return $this->fields_displayed_default;
    }

    /**
     * Set the default list of displayed fields.
     *
     * @param string[] $fields_displayed_default
     */
    public function setFieldsDisplayedDefault(array $fields_displayed_default): AbstractTable
    {
        $this->fields_displayed_default = $fields_displayed_default;

        return $this;
    }

    /**
     * Get all fields.
     *
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get the table alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->generateAlias($this->getClass());
    }

    /**
     * Generate alias based on classname.
     *
     * @param $classname
     *
     * @return string
     */
    public function generateAlias($classname)
    {
        $class_part = explode('\\', $classname);

        return strtolower(array_pop($class_part));
    }

    /**
     * Get main class of table.
     *
     * @return string $class
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param Filter[] $filters
     */
    public function setFilters(array $filters): AbstractTable
    {
        $this->filters = $filters;

        return $this;
    }

    public function isDisplayMenu(): bool
    {
        return $this->display_menu;
    }

    public function setDisplayMenu(bool $display_menu): AbstractTable
    {
        $this->display_menu = $display_menu;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplayMenuLabel()
    {
        return $this->display_menu_label;
    }

    /**
     * @param bool $display_menu_label
     *
     * @return AbstractTable
     */
    public function setDisplayMenuLabel($display_menu_label)
    {
        $this->display_menu_label = $display_menu_label;

        return $this;
    }

    public function getTemplateFields(): string
    {
        return $this->templateFields;
    }

    /**
     * @param string $templateFields
     */
    public function setTemplateFields($templateFields): AbstractTable
    {
        $this->templateFields = $templateFields;

        return $this;
    }

    /**
     * Add field in the table.
     */
    public function addField(Field $field, Jointure $jointure = null)
    {
        $field->setJointure($jointure);
        $field->setTable($this);
        $this->fields[$field->getId()] = $field;
    }

    /**
     * Remove a field from the table.
     *
     * @param string $id
     *
     * @throws FieldNotExistException
     */
    public function removeField($id)
    {
        $this->getField($id);
        unset($this->fields[$id]);

        if (null !== $this->fields_displayed) {
            $position = array_search($id, $this->fields_displayed);
            if (false !== $position) {
                unset($this->fields_displayed[$position]);
            }
        }
    }

    /**
     * Retrieve field with given id.
     *
     * @param $id
     *
     * @return Field
     *
     * @throws FieldNotExistException
     */
    public function getField($id)
    {
        if (!isset($this->fields[$id])) {
            throw new FieldNotExistException($id);
        }

        return $this->fields[$id];
    }

    /**
     * @return string[]
     */
    public function getTableClasses(): array
    {
        return $this->table_classes;
    }

    /**
     * @param string[] $table_classes
     */
    public function setTableClasses(array $table_classes): AbstractTable
    {
        $this->table_classes = $table_classes;

        return $this;
    }

    /**
     * @param string[] $table_classes
     */
    public function addTableClasse($table_classe): AbstractTable
    {
        $this->table_classes[] = $table_classe;

        return $this;
    }

    /**
     * Retrieve list of fields not displayed.
     *
     * @return string[]
     */
    public function getFieldsAvailable()
    {
        $return = array();
        foreach (array_keys($this->getFieldsVisibles()) as $fieldname) {
            if (false === array_search($fieldname, $this->getFieldsDisplayed())) {
                $return[] = $fieldname;
            }
        }

        return $return;
    }

    /**
     * Get visible fields.
     *
     * @return Field[]
     */
    public function getFieldsVisibles()
    {
        $return = array();
        foreach ($this->getFields() as $key => $field) {
            if ($field->isVisible()) {
                $return[$key] = $field;
            }
        }

        return $return;
    }

    /**
     * Get displayed fields.
     *
     * @return mixed
     */
    public function getFieldsDisplayed()
    {
        if (is_null($this->fields_displayed)) {
            $this->fields_displayed = array_keys($this->fields);
        }

        return $this->fields_displayed;
    }

    /**
     * Get max items per page.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Retrieve the paginator.
     *
     * @return PaginatorInterface
     */
    public function getPaginator()
    {
        return $this->paginator_calculated;
    }

    /**
     * Render the table.
     *
     * @return string
     *
     * @throws Throwable
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render()
    {
        $this->handleRequest();

        $this->generateSearchForm();
        $this->generate();

        $template = $this->twig->load($this->getTemplate());

        return $template->renderBlock('table', array('table' => $this));
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): AbstractTable
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Retrieve the value of the Field.
     *
     * @param $object
     *
     * @return mixed
     */
    public function getValue($object, Field $field)
    {
        $prefix = 'get';
        $converter = new CamelCaseToSnakeCaseNameConverter();
        if (!is_null($field->getJointure())) {
            $tmp = $this->getObject($object, $field->getJointure());
            if (is_null($tmp)) {
                return '';
            }
            if (is_iterable($tmp)) {
                return $tmp;
            }
            if (method_exists($tmp, 'is'.$converter->denormalize($field->getField()))) {
                $prefix = 'is';
            }
            $value = call_user_func(array($tmp, $prefix.$converter->denormalize($field->getField())));
        } else {
            if (method_exists($object, 'is'.$converter->denormalize($field->getField()))) {
                $prefix = 'is';
            }
            $value = call_user_func(array($object, $prefix.$converter->denormalize($field->getField())));
        }

        return $value;
    }

    /**
     * Get the search form.
     *
     * @return FormView
     */
    public function getSearchForm()
    {
        return $this->search_form->createView();
    }

    /**
     * @param Filter[] $filters
     */
    public function addFilter(Filter $filter): AbstractTable
    {
        $this->filters[] = $filter;
        $this->generate();

        return $this;
    }

    public function isDisplayColumnSelector(): bool
    {
        return $this->display_column_selector;
    }

    public function setDisplayColumnSelector(bool $display_column_selector): AbstractTable
    {
        $this->display_column_selector = $display_column_selector;

        return $this;
    }

    public function isDisplayPagination(): bool
    {
        return $this->display_pagination;
    }

    public function setDisplayPagination(bool $display_pagination): AbstractTable
    {
        $this->display_pagination = $display_pagination;

        return $this;
    }

    public function isDisplayTotalItems(): bool
    {
        return $this->display_total_items;
    }

    public function setDisplayTotalItems(bool $display_total_items): AbstractTable
    {
        $this->display_total_items = $display_total_items;

        return $this;
    }

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param Action[] $actions
     */
    public function setActions(array $actions): AbstractTable
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Add an action.
     */
    public function addAction(Action $action)
    {
        $this->actions[] = $action;
    }

    /**
     * @param array $sort array(field => order)
     *
     * @return AbstractTable
     */
    public function setDefaultSort(array $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Generate the paginator base on table parameters.
     */
    protected function generate()
    {
        $request = $this->request_stack->getCurrentRequest();
        $query = $this->generateQuery();
        $this->paginator_calculated = $this->paginator->paginate($query, $request->query->get('page', 1), $this->limit);
    }

    /**
     * Allow the developper to add a Custom part to the query.
     *
     * @param QueryBuilder $qb the query builder to update
     */
    protected function addCustomQueryPart(QueryBuilder $qb)
    {
    }

    /**
     * Define main class of table.
     *
     * @param string $class
     */
    protected function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Retrieve jointures from the given table.
     *
     * @return Jointure[]
     */
    protected function getJointures()
    {
        return $this->jointures;
    }

    /**
     * Set displayed fields.
     */
    protected function setFieldsDisplayed(array $fields)
    {
        $this->fields_displayed = $fields;
    }

    /**
     * Define max items per page.
     *
     * @param $limit
     */
    protected function setLimit($limit)
    {
        $this->limit = $limit;
    }

    protected function getDoctrine(): RegistryInterface
    {
        return $this->doctrine;
    }

    protected function setDoctrine(RegistryInterface $doctrine): AbstractTable
    {
        $this->doctrine = $doctrine;

        return $this;
    }

    /**
     * Add a jointure to the given table.
     *
     * @return $this
     */
    protected function addJointure(Jointure $jointure)
    {
        $this->jointures[$jointure->getId()] = $jointure;

        return $this;
    }

    private function setConfig(array $config)
    {
        $this->setTemplate($config['template']['table']);
        $this->setTemplateFields($config['template']['fields']);
    }

    private function getSessionAttribute($name)
    {
        $field = $this->request_stack->getCurrentRequest()->get('_route').'_'.$name;

        return $this->session->get($field);
    }

    private function setSessionAttribute($name, $value)
    {
        $field = $this->request_stack->getCurrentRequest()->get('_route').'_'.$name;
        $this->session->set($field, $value);
    }

    /**
     * Handle the request and update table settings.
     */
    private function handleRequest()
    {
        $current_request = $this->request_stack->getCurrentRequest();

        $action = $current_request->query->get('action', null);

        switch ($action) {
            case 'remove_field':
                $field = $current_request->query->get('field', null);
                if (null === $field) {
                    break;
                }
                $fields = $this->getSessionAttribute(self::FIELDS_DISPLAYED);
                if (false !== ($key = array_search($field, $fields))) {
                    unset($fields[$key]);
                }
                $this->setSessionAttribute(self::FIELDS_DISPLAYED, $fields);
                $current_request->query->remove('field');
                break;
            case 'add_field':
                $field = $current_request->get('field', null);
                if (null === $field) {
                    break;
                }
                $old_fields = $this->getSessionAttribute(self::FIELDS_DISPLAYED);
                if (false === array_search($field, $old_fields)) {
                    $new_fields = array();
                    foreach ($old_fields as $tmp) {
                        $index = array_search($tmp, array_keys($this->getFields()));
                        $new_fields[$index] = $tmp;
                    }
                    $index = array_search($field, array_keys($this->getFields()));
                    $new_fields[$index] = $field;
                    ksort($new_fields);
                } else {
                    $new_fields = $old_fields;
                }
                $this->setSessionAttribute(self::FIELDS_DISPLAYED, $new_fields);
                $current_request->query->remove('field');
                break;
            case 'reset_field':
                $this->setSessionAttribute(self::FIELDS_DISPLAYED, $this->getFieldsDisplayedDefault());
                break;
        }

        $this->setFieldsDisplayed($this->getSessionAttribute(self::FIELDS_DISPLAYED));

        $current_request->query->remove('action');
    }

    private function generateSearchForm()
    {
        $this->search_form = $this->formFactory->create(SearchForm::class, null, array(
            'method' => 'GET',
            'doctrine' => $this->doctrine,
            'table' => $this,
        ));
        $this->search_form->handleRequest($this->request_stack->getCurrentRequest());
    }

    /**
     * Generate the query based on table parameters.
     *
     * @return Query
     */
    private function generateQuery()
    {
        $alias = $this->getAlias();

        $qb = $this->doctrine->getRepository($this->getClass())->createQueryBuilder($alias);

        $this->addCustomQueryPart($qb);

        foreach ($this->getJointures() as $jointure) {
            $qb->leftJoin($jointure->getDQL($alias), strtolower($jointure->getId()))->addSelect(strtolower($jointure->getId()));
        }

        foreach ($this->sort as $field => $value) {
            $qb->addOrderBy($alias.'.'.$field, $value);
        }

        foreach ($this->getFilters() as $key => $filter) {
            $qb->andWhere($filter->getDQL($key, $this->getAlias()));
            foreach ($filter->getDQLParameters($key) as $k => $v) {
                $qb->setParameter($k, $v);
            }
        }

        if (!is_null($this->search_form) && $this->search_form->isSubmitted() && $this->search_form->isValid()) {
            SearchForm::applyToQueryBuilder($this->search_form, $qb, $this);
        }

        return $qb->getQuery();
    }

    /**
     * Retrieve the object in relation with the given Jointure.
     *
     * @param $object
     *
     * @return mixed
     */
    private function getObject($object, Jointure $jointure)
    {
        $converter = new CamelCaseToSnakeCaseNameConverter();

        if (is_null($jointure->getParent())) {
            return call_user_func(array($object, 'get'.$converter->denormalize($jointure->getField())));
        }

        $tmp = $this->getObject($object, $jointure->getParent());

        return call_user_func(array($tmp, 'get'.$converter->denormalize($jointure->getField())));
    }
}
