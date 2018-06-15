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

use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                    'field_class'        => null,
                    'field_types'        => [],
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
                FieldsCollectionType::class,
                [
                    'field_types'    => $options['field_types'] ?? [],
                    'entry_type'     => FieldEditType::class,
                    'entry_options'  => [
                        'field_types'    => $options['field_types'] ?? [],
                    ],
                    'prototype_data' => $options['field_class'],
                    'allow_add'      => true,
                    'allow_delete' => true,
                    'label'          => 'novaformbuilder_form.fields',
                    'by_reference' => false, // we need this to force framework set form_id in fields
                ]
            );
    }
}
