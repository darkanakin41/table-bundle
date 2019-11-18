<?php


namespace Darkanakin41\TableBundle\Extension;

use Darkanakin41\TableBundle\Definition\AbstractTable;
use Darkanakin41\TableBundle\Definition\Field;
use Darkanakin41\TableBundle\Exception\UnknownBlockException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TableExtension extends AbstractExtension
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
            new TwigFunction('darkanakin41_table_render', [$this, 'render'], ['is_safe' => ['html']]),
            new TwigFunction('darkanakin41_table_render_field', [$this, 'renderField'], ['is_safe' => ['html']]),
        );
    }

    /**
     * Render the Table
     *
     * @param AbstractTable $table
     *
     * @return string
     * @throws Throwable
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(AbstractTable $table)
    {
        return $table->render();
    }

    /**
     * Render the field
     *
     * @param AbstractTable $table
     * @param Field         $field
     * @param mixed         $item
     *
     * @return string
     * @throws UnknownBlockException
     */
    public function renderField(AbstractTable $table, Field $field, $item)
    {
        $template = $this->container->get("twig")->load($table->getTemplateFields());
        if (!$template->hasBlock($field->getBlock())) {
            throw new UnknownBlockException("Unknown block");
        }
        return $template->renderBlock($field->getBlock(), array('table' => $table, 'field' => $field, 'item' => $item));
    }
}
