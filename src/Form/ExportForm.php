<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Form;

use Darkanakin41\TableBundle\Definition\AbstractTable;
use Darkanakin41\TableBundle\Definition\Field;
use Darkanakin41\TableBundle\Fields\CountryField;
use Darkanakin41\TableBundle\Fields\DateTimeField;
use Darkanakin41\TableBundle\Form\Type\RangeSelectorType;
use Darkanakin41\TableBundle\Helper\ExportTableHelper;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExportForm extends AbstractType
{

    /**
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('format', ChoiceType::class, [
            'label' => 'export.format',
            'placeholder' => 'export.format',
            'choices' => ExportTableHelper::AVAILABLE_FORMATS,
            'required' => true,
        ]);

        $builder->add('content', ChoiceType::class, [
            'label' => 'export.content',
            'placeholder' => 'export.content',
            'choices' => ExportTableHelper::CONTENT_SELECTION,
            'required' => true,
        ]);

        $builder->add('submit', SubmitType::class, array(
            'label' => 'action.export',
            'attr' => array(
                'class' => 'button success',
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('csrf_protection', false);
    }
}
