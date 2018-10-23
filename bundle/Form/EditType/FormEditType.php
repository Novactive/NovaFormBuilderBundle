<?php
/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Form\EditType;

use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormEditType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'novaformbuilder_form_edit';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'data_class'         => Form::class,
                    'translation_domain' => 'novaformbuilder',
                    'field_class'        => null,
                    'field_types'        => [],
                ]
            );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
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
                        'field_types' => $options['field_types'] ?? [],
                    ],
                    'prototype_data' => $options['field_class'],
                    'allow_add'      => true,
                    'allow_delete'   => true,
                    'label'          => 'novaformbuilder.fields',
                    'by_reference'   => false, // we need this to force framework set form_id in fields
                ]
            );
    }
}
