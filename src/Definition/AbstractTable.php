<?php

namespace Darkanakin41\TableBundle\Definition;

use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;
use Darkanakin41\TableBundle\Exception\FieldNotExistException;
use Darkanakin41\TableBundle\Form\SearchForm;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
    const FIELDS_DISPLAYED = "fields_displayed";

    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * @var string[]
     */
    private $fields_displayed = [];

    /**
     * @var string[]
     */
    private $fields_displayed_default = [];

    /**
     * @var Jointure[]
     */
    private $jointures = [];

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
     * @var integer
     */
    private $limit;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string[]
     */
    private $sort = [];

    /**
     * @var Filter[]
     */
    private $filters = [];

    /**
     * @var Form
     */
    private $search_form;

    /**
     * @var boolean
     */
    private $display_column_selector;

    /**
     * @var boolean
     */
    private $display_pagination;

    /**
     * @var boolean
     */
    private $display_menu;

    /**
     * @var boolean
     */
    private $display_total_items;
    /**
     * @var boolean
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
    private $table_classes = [];
    /**
     * @var Action[]
     */
    private $actions = [];

    public function __construct(PaginatorInterface $paginator, RequestStack $request_stack, RegistryInterface $doctrine, Twig_Environment $twigEnvironment, SessionInterface $session, FormFactoryInterface $formFactory, ContainerInterface $container)
    {
        $this->paginator = $paginator;
        $this->request_stack = $request_stack;
        $this->setDoctrine($doctrine);
        $this->twig = $twigEnvironment;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->fields = [];
        $this->jointures = [];
        $this->filters = [];
        $this->sort = [];

        $this->setConfig($container->getParameter('darkanakin41.table.config'));

        $this->setTableClasses([]);
        $this->setActions([]);

        $this->setDisplayMenu(TRUE);
        $this->setDisplayColumnSelector(TRUE);
        $this->setDisplayPagination(TRUE);
        $this->setDisplayTotalItems(TRUE);
        $this->setDisplayMenuLabel(FALSE);

        $this->setLimit(10);
        $this->__init__();

        if (empty($this->getSessionAttribute(self::FIELDS_DISPLAYED))) {
            $this->setSessionAttribute(self::FIELDS_DISPLAYED, $this->getFieldsDisplayedDefault());
        }
        $this->handleRequest();

        $this->setFieldsDisplayed($this->getSessionAttribute(self::FIELDS_DISPLAYED));

        $this->generateSearchForm();
        $this->generate();
    }

    private function setConfig(array $config)
    {
        $this->setTemplate($config['template']['table']);
        $this->setTemplateFields($config['template']['fields']);
    }

    /**
     * Intialise all fields of the table and so on
     */
    abstract protected function __init__();

    private function getSessionAttribute($name)
    {
        $field = $this->request_stack->getCurrentRequest()->get("_route")."_".$name;
        return $this->session->get($field);
    }

    private function setSessionAttribute($name, $value)
    {
        $field = $this->request_stack->getCurrentRequest()->get("_route")."_".$name;
        $this->session->set($field, $value);
    }

    /**
     * @return string[]
     */
    public function getFieldsDisplayedDefault(): array
    {
        return $this->fields_displayed_default;
    }

    /**
     * Set the default list of displayed fields
     *
     * @param string[] $fields_displayed_default
     *
     * @return AbstractTable
     */
    public function setFieldsDisplayedDefault(array $fields_displayed_default): AbstractTable
    {
        $this->fields_displayed_default = $fields_displayed_default;
        return $this;
    }

    /**
     * Handle the request and update table settings
     */
    private function handleRequest()
    {
        $current_request = $this->request_stack->getCurrentRequest();

        $action = $current_request->query->get("action", NULL);

        switch ($action) {
            case "remove_field" :
                $field = $current_request->query->get("field", NULL);
                if ($field === NULL) break;
                $fields = $this->getSessionAttribute(self::FIELDS_DISPLAYED);
                if (($key = array_search($field, $fields)) !== FALSE) unset($fields[$key]);
                $this->setSessionAttribute(self::FIELDS_DISPLAYED, $fields);
                $current_request->query->remove("field");
                break;
            case "add_field":
                $field = $current_request->get("field", NULL);
                if ($field === NULL) break;
                $old_fields = $this->getSessionAttribute(self::FIELDS_DISPLAYED);
                if (array_search($field, $old_fields) === FALSE) {
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
                $current_request->query->remove("field");
                break;
            case "reset_field":
                $this->setSessionAttribute(self::FIELDS_DISPLAYED, $this->getFieldsDisplayedDefault());
                break;
        }

        $current_request->query->remove("action");
    }

    /**
     * Get all fields
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    private function generateSearchForm()
    {
        $this->search_form = $this->formFactory->create(SearchForm::class, NULL, array(
            "method" => "GET",
            "doctrine" => $this->doctrine,
            "table" => $this,
        ));
        $this->search_form->handleRequest($this->request_stack->getCurrentRequest());
    }

    /**
     * Generate the paginator base on table parameters
     */
    protected function generate()
    {
        $request = $this->request_stack->getCurrentRequest();
        $query = $this->generateQuery();
        $this->paginator_calculated = $this->paginator->paginate($query, $request->query->get('page', 1), $this->limit);
    }

    /**
     * Generate the query based on table parameters
     * @return Query
     */
    private function generateQuery()
    {
        $alias = $this->getAlias();

        $qb = $this->doctrine->getRepository($this->getClass())->createQueryBuilder($alias);

        foreach ($this->getJointures() as $jointure) {
            $qb->leftJoin($jointure->getDQL($alias), strtolower($jointure->getId()))->addSelect(strtolower($jointure->getId()));
        }

        foreach ($this->sort as $field => $value) {
            $qb->addOrderBy($alias.".".$field, $value);
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
     * Get the table alias
     * @return string
     */
    public function getAlias()
    {
        return $this->generateAlias($this->getClass());
    }

    /**
     * Generate alias based on classname
     *
     * @param $classname
     *
     * @return string
     */
    public function generateAlias($classname)
    {
        $class_part = explode("\\", $classname);
        return strtolower(array_pop($class_part));
    }

    /**
     * Get main class of table
     * @return string $class
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Define main class of table
     *
     * @param string $class
     */
    protected function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Retrieve jointures from the given table
     * @return Jointure[]
     */
    protected function getJointures()
    {
        return $this->jointures;
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
     *
     * @return AbstractTable
     */
    public function setFilters(array $filters): AbstractTable
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplayMenu(): bool
    {
        return $this->display_menu;
    }

    /**
     * @param bool $display_menu
     *
     * @return AbstractTable
     */
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

    /**
     * @return string
     */
    public function getTemplateFields(): string
    {
        return $this->templateFields;
    }

    /**
     * @param string $templateFields
     *
     * @return AbstractTable
     */
    public function setTemplateFields($templateFields): AbstractTable
    {
        $this->templateFields = $templateFields;
        return $this;
    }

    /**
     * Add field in the table
     *
     * @param Field $field
     */
    public function addField(Field $field, Jointure $jointure = NULL)
    {
        $field->setJointure($jointure);
        $field->setTable($this);
        $this->fields[$field->getId()] = $field;
    }

    /**
     * Remove a field from the table
     *
     * @param string $id
     *
     * @throws FieldNotExistException
     */
    public function removeField($id)
    {
        $this->getField($id);
        unset($this->fields[$id]);

        if($this->fields_displayed !== null){
            $position = array_search($id, $this->fields_displayed);
            if($position !== false){
                unset($this->fields_displayed[$position]);
            }
        }
    }

    /**
     * Retrieve field with given id
     *
     * @param $id
     *
     * @return Field
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
     *
     * @return AbstractTable
     */
    public function setTableClasses(array $table_classes): AbstractTable
    {
        $this->table_classes = $table_classes;
        return $this;
    }

    /**
     * @param string[] $table_classes
     *
     * @return AbstractTable
     */
    public function addTableClasse($table_classe): AbstractTable
    {
        $this->table_classes[] = $table_classe;
        return $this;
    }

    /**
     * Retrieve list of fields not displayed
     * @return string[]
     */
    public function getFieldsAvailable()
    {
        $return = array();
        foreach (array_keys($this->getFieldsVisibles()) as $fieldname) {
            if (array_search($fieldname, $this->getFieldsDisplayed()) === FALSE) {
                $return[] = $fieldname;
            }
        }

        return $return;
    }

    /**
     * Get visible fields
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
     * Get displayed fields
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
     * Set displayed fields
     *
     * @param array $fields
     */
    protected function setFieldsDisplayed(array $fields)
    {
        $this->fields_displayed = $fields;
    }

    /**
     * Get max items per page
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Define max items per page
     *
     * @param $limit
     */
    protected function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * Retrieve the paginator
     * @return PaginatorInterface
     */
    public function getPaginator()
    {
        return $this->paginator_calculated;
    }

    /**
     * Render the table
     * @return string
     * @throws Throwable
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render()
    {
        $template = $this->twig->load($this->getTemplate());
        return $template->renderBlock("table", array('table' => $this));
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return AbstractTable
     */
    public function setTemplate(string $template): AbstractTable
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Retrieve the value of the Field
     *
     * @param       $object
     * @param Field $field
     *
     * @return mixed
     */
    public function getValue($object, Field $field)
    {
        $prefix = "get";
        $converter = new CamelCaseToSnakeCaseNameConverter();
        if (!is_null($field->getJointure())) {
            $tmp = $this->getObject($object, $field->getJointure());
            if (is_null($tmp)) {
                return "";
            }
            if (is_iterable($tmp)) {
                return $tmp;
            }
            if (method_exists($tmp, "is".$converter->denormalize($field->getField()))) {
                $prefix = "is";
            }
            $value = call_user_func(array($tmp, $prefix.$converter->denormalize($field->getField())));
        } else {
            if (method_exists($object, "is".$converter->denormalize($field->getField()))) {
                $prefix = "is";
            }
            $value = call_user_func(array($object, $prefix.$converter->denormalize($field->getField())));
        }
        return $value;
    }

    /**
     * Retrieve the object in relation with the given Jointure
     *
     * @param          $object
     * @param Jointure $jointure
     *
     * @return mixed
     */
    private function getObject($object, Jointure $jointure)
    {
        $converter = new CamelCaseToSnakeCaseNameConverter();

        if (is_null($jointure->getParent())) {
            return call_user_func(array($object, "get".$converter->denormalize($jointure->getField())));
        }

        $tmp = $this->getObject($object, $jointure->getParent());
        return call_user_func(array($tmp, "get".$converter->denormalize($jointure->getField())));
    }

    /**
     * Get the search form
     * @return FormView
     */
    public function getSearchForm()
    {
        return $this->search_form->createView();
    }

    /**
     * @param Filter[] $filters
     *
     * @return AbstractTable
     */
    public function addFilter(Filter $filter): AbstractTable
    {
        $this->filters[] = $filter;
        $this->generate();
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplayColumnSelector(): bool
    {
        return $this->display_column_selector;
    }

    /**
     * @param bool $display_column_selector
     *
     * @return AbstractTable
     */
    public function setDisplayColumnSelector(bool $display_column_selector): AbstractTable
    {
        $this->display_column_selector = $display_column_selector;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplayPagination(): bool
    {
        return $this->display_pagination;
    }

    /**
     * @param bool $display_pagination
     *
     * @return AbstractTable
     */
    public function setDisplayPagination(bool $display_pagination): AbstractTable
    {
        $this->display_pagination = $display_pagination;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplayTotalItems(): bool
    {
        return $this->display_total_items;
    }

    /**
     * @param bool $display_total_items
     *
     * @return AbstractTable
     */
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
     *
     * @return AbstractTable
     */
    public function setActions(array $actions): AbstractTable
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * Add an action
     *
     * @param Action $action
     */
    public function addAction(Action $action)
    {
        $this->actions[] = $action;
    }

    /**
     * @return RegistryInterface
     */
    protected function getDoctrine(): RegistryInterface
    {
        return $this->doctrine;
    }

    /**
     * @param RegistryInterface $doctrine
     *
     * @return AbstractTable
     */
    protected function setDoctrine(RegistryInterface $doctrine): AbstractTable
    {
        $this->doctrine = $doctrine;
        return $this;
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
     * Add a jointure to the given table
     *
     * @param Jointure $jointure
     *
     * @return $this
     */
    protected function addJointure(Jointure $jointure)
    {
        $this->jointures[$jointure->getId()] = $jointure;
        return $this;
    }
}
