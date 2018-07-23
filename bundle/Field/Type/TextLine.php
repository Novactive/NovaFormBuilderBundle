<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field\Type;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;

class TextLine extends FieldType
{
    /**
     * @param array $properties
     *
     * @return Field
     */
    public function getEntity(array $properties = []): Field
    {
        return new Field\TextLine($properties);
    }

    /**
     * @param Field $field
     *
     * @return bool
     */
    public function accept(Field $field): bool
    {
        return $field instanceof Field\TextLine;
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
                    'label'      => 'novaformbuilder_field.textline.min_length',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            )
            ->add(
                'maxLength',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'novaformbuilder_field.textline.max_length',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            );
    }

    /**
     * @inheritDoc
     * @param Field\TextLine $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                TextType::class,
                [
                    'required'   => $field->isRequired(),
                    'label'      => 'novaformbuilder_field.textline.value',
                    'attr'       => [
                        'min' => $field->getMinLength(),
                        'max' => $field->getMaxLength()
                    ],
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
