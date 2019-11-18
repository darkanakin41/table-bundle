<?php

namespace Darkanakin41\TableBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RangeSelectorType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('min', HiddenType::class, array(
            'required' => TRUE,
            'label' => FALSE,
            'attr' => array(
                'readonly' => ''
            )
        ));
        $builder->add('max', HiddenType::class, array(
            'required' => TRUE,
            'label' => FALSE,
            'attr' => array(
                'readonly' => ''
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['min'] = $options['min'];
        $view->vars['max'] = $options['max'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'min' => 0,
            'max' => 100,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'range_selector';
    }
}
