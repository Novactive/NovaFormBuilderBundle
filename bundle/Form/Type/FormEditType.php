<?php
/**
 * NovaFormBuilderBundle.
 *
 * @package   NovaFormBuilderBundle
 *
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE
 */

namespace Novactive\Bundle\FormBuilderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Symfony\Component\Form\{FormEvent, FormEvents};

class FormEditType extends AbstractType
{
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'novaformbuilder_form_edit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'data_class'         => Form::class,
                    'translation_domain' => 'novaformbuilder_form',
                    'field_class' => null
                ]
            );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('maxSubmissions')
            ->add(
            'fields',
            CollectionType::class,
            [
                'entry_type'    => FieldEditType::class,
                'entry_options' => [
                    'label' => 'test'
                ],
                'prototype_data' => $options['field_class'],
                'allow_add' => true,
                'label'         => 'novaformbuilder_form.field',
            ]
        );
    }
}
