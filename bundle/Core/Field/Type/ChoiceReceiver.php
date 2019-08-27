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

namespace Novactive\Bundle\FormBuilderBundle\Core\Field\Type;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Form\Type\ChoiceItemType;
use Novactive\Bundle\FormBuilderBundle\Form\Type\ChoiceReceiverType;
use Novactive\Bundle\FormBuilderBundle\Form\Type\WeightedCollectionType;
use Symfony\Component\Form\FormInterface;

class ChoiceReceiver extends Choice
{
    public function getEntityClass(): string
    {
        return Field\ChoiceReceiver::class;
    }

    /**
     * @param Field\ChoiceReceiver $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'choice_type',
                ChoiceReceiverType::class,
                [
                    'choices'      => [
                        'radio'      => 'radio',
                        'checkboxes' => 'checkboxes',
                        'dropdown'   => 'dropdown',
                    ],
                    'choice_label' => function ($choiceValue, $key, $value) {
                        return 'field.choice.choice_type.'.$key;
                    },
                    'label'        => 'field.choice.choice_type',
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
                    'label'          => 'field.choice.choices',
                    'prototype_name' => '__choice_name__',
                ]
            );
    }
}
