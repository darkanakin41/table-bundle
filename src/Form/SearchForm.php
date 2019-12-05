<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Form;

use Darkanakin41\TableBundle\Definition\AbstractTable;
use Darkanakin41\TableBundle\Definition\Field;
use Darkanakin41\TableBundle\Fields\CountryField;
use Darkanakin41\TableBundle\Form\Type\RangeSelectorType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    /**
     * @var AbstractTable
     */
    private $table;

    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public static function applyToQueryBuilder(FormInterface $form, QueryBuilder $qb, AbstractTable $table)
    {
        foreach ($table->getFields() as $field) {
            if (!$field->isFilterable()) {
                continue;
            }
            if (!empty($form->get($field->getId())->getData())) {
                $value = $form->get($field->getId())->getData();
                if ($field->isNumeric()) {
                    $qb->andWhere($field->getDQL($table->getAlias()).' BETWEEN :'.$field->getId().'_min AND :'.$field->getId().'_max');
                    $qb->setParameter($field->getId().'_min', $value['min']);
                    $qb->setParameter($field->getId().'_max', $value['max']);
                } else {
                    $dql = $field->getQBFilter($table, $qb);
                    if (false !== stripos($dql, 'LIKE')) {
                        $value = "%$value%";
                    }
                    $qb->andWhere($dql);
                    $qb->setParameter($field->getId(), $value);
                }
            }
        }
    }

    public function getTable(): AbstractTable
    {
        return $this->table;
    }

    public function setTable(AbstractTable $table): SearchForm
    {
        $this->table = $table;

        return $this;
    }

    public function getDoctrine(): RegistryInterface
    {
        return $this->doctrine;
    }

    public function setDoctrine(RegistryInterface $doctrine): SearchForm
    {
        $this->doctrine = $doctrine;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (is_null($options['doctrine'])) {
            throw new \Exception('Please provide doctrine');
        }
        if (is_null($options['table'])) {
            throw new \Exception('Please provide the table');
        }
        $this->table = $options['table'];
        $this->doctrine = $options['doctrine'];

        foreach ($this->table->getFields() as $fieldname => $field) {
            if (!$field->isFilterable()) {
                continue;
            }
            $settings = array(
                'label' => $fieldname,
                'required' => false,
                'attr' => array(
                    'placeholder' => $fieldname,
                ),
            );
            $classname = TextType::class;
            if ($field->isChoice()) {
                $classname = ChoiceType::class;
                if (!empty($field->getValueToLabels())) {
                    $settings['choices'] = array_flip($field->getValueToLabels());
                } else {
                    $settings['choices'] = $this->getDistinctValues($field);
                }
            } elseif ($field->isNumeric()) {
                $classname = RangeSelectorType::class;
                $values = $this->getDistinctValues($field);
                $settings['min'] = min($values);
                $settings['max'] = max($values);
            } elseif ($field instanceof CountryField) {
                $classname = CountryType::class;
            }

            if (!is_null($field->getTranslationPrefix())) {
                $settings['choice_label'] = function ($choiceValue, $key, $value) use ($field) {
                    return $field->getTranslationPrefix().$key;
                };
            }
            if (isset($settings['choices']) && !$field->isTranslation()) {
                $settings['choice_translation_domain'] = false;
            }

            $builder->add($fieldname, $classname, $settings);
        }

        $builder->add('submit', SubmitType::class, array(
            'label' => 'action.search',
            'attr' => array(
                'class' => 'button success',
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('doctrine');
        $resolver->setRequired('table');
    }

    private function getDistinctValues(Field $field)
    {
        $class = $this->table->getClass();
        if (!is_null($field->getJointure())) {
            $class = $field->getJointure()->getClass();
        }

        /** @var ObjectManager $manager */
        $manager = $this->getDoctrine()->getManagerForClass($class);
        $dql = sprintf('SELECT DISTINCT i.%s FROM %s i ORDER BY i.%s ASC', $field->getField(), $class, $field->getField());

        /** @var Query $query */
        $query = $manager->createQuery($dql);
        $values = $query->getResult();
        $retour = array();
        foreach ($values as $value) {
            $final_value = $field->getValue($value[$field->getField()]);
            if (!empty($field->getValueToLabels())) {
                $final_value = $field->getValueToLabel($final_value);
            }
            if ($field->isTranslation()) {
                $retour[strtolower($final_value)] = $final_value;
            } else {
                $retour[$final_value] = $final_value;
            }
        }

        return $retour;
    }
}
