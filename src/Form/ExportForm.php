<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Form;

use Darkanakin41\TableBundle\Helper\ExportTableHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExportForm extends AbstractType
{
    /**
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('format', ChoiceType::class, array(
            'label' => 'export.format',
            'placeholder' => 'export.format',
            'choices' => ExportTableHelper::AVAILABLE_FORMATS,
            'required' => true,
        ));

        $builder->add('content', ChoiceType::class, array(
            'label' => 'export.content',
            'placeholder' => 'export.content',
            'choices' => ExportTableHelper::CONTENT_SELECTION,
            'required' => true,
        ));

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
