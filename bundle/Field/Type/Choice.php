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

namespace Novactive\Bundle\FormBuilderBundle\Field\Type;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldType;
use Novactive\Bundle\FormBuilderBundle\Form\Type\ChoiceItemType;
use Novactive\Bundle\FormBuilderBundle\Form\Type\WeightedCollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;

class Choice extends FieldType
{
    public function getEntity(array $properties = []): Field
    {
        return new Field\Choice($properties);
    }

    public function supports(Field $field): bool
    {
        return $field instanceof Field\Choice;
    }

    /**
     * @param Field\Choice $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'choice_type',
                ChoiceType::class,
                [
                    'choices'      => [
                        'radio'      => 'radio',
                        'checkboxes' => 'checkboxes',
                        'dropdown'   => 'dropdown',
                    ],
                    'choice_label' => function ($choiceValue, $key, $value) {
                        return 'novaformbuilder.field.choice.choice_type.'.$key;
                    },
                    'label'        => 'novaformbuilder.field.choice.choice_type',
                ]
            )
            ->add(
                'choices',
                WeightedCollectionType::class,
                [
                    'entry_type'     => ChoiceItemType::class,
                    'entry_options'  => [
                        'empty_data' => [],
                        'attr'       => [
                            'class' => 'choice_item',
                        ],
                    ],
                    'delete_empty'   => true,
                    'empty_data'     => [],
                    'allow_add'      => true,
                    'allow_delete'   => true,
                    'required'       => true,
                    'label'          => 'novaformbuilder.field.choice.choices',
                    'prototype_name' => '__choice_name__',
                ]
            );
    }

    /**
     * @param Field\Choice $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $formattedChoices = [];
        foreach ($field->getChoices() as $choice) {
            $formattedChoices[$choice['value']] = $choice['value'];
        }

        $fieldForm->add(
            'value',
            ChoiceType::class,
            [
                'required' => $field->isRequired(),
                'label'    => $field->getName(),
                'choices'  => $formattedChoices,
            ]
        );
    }
}
