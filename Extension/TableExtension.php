<?php


namespace PLejeune\TableBundle\Extension;

use PLejeune\TableBundle\Definition\AbstractTable;
use PLejeune\TableBundle\Definition\Field;
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
            new TwigFunction('table_render', [$this, 'render'], ['is_safe' => ['html']]),
            new TwigFunction('table_render_field', [$this, 'renderField'], ['is_safe' => ['html']]),
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
     */
    public function renderField(AbstractTable $table, Field $field, $item)
    {
        foreach (array_reverse($table->getTemplateFields()) as $template) {
            $template = $this->container->get("twig")->load($template);
            if (!$template->hasBlock($field->getBlock())) {
                continue;
            }
            return $template->renderBlock($field->getBlock(), array('table' => $table, 'field' => $field, 'item' => $item));
        }
        return "";
    }
}
