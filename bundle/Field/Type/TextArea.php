<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field\Type;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;

class TextArea extends FieldType
{
    /**
     * @param array $properties
     *
     * @return Field
     */
    public function getEntity(array $properties = []): Field
    {
        return new Field\TextArea($properties);
    }

    /**
     * @param Field $field
     *
     * @return bool
     */
    public function accept(Field $field): bool
    {
        return $field instanceof Field\TextArea;
    }

    /**
     * @inheritDoc
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'minLength',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'novaformbuilder_field.textarea.min_length',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            )
            ->add(
                'maxLength',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'novaformbuilder_field.textarea.max_length',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            );
    }


    /**
     * @param FormInterface $fieldForm
     * @param Field\TextArea $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                TextareaType::class,
                [
                    'required'   => $field->isRequired(),
                    'label'      => 'novaformbuilder_field.textline.value',
                    'empty_data' => 0,
                    'constraints' => [
                        new Length([
                            'min' => $field->getMinLength() ?: null,
                            'max' => $field->getMaxLength() ?: null,
                        ])
                    ]
                ]
            );
    }
}
