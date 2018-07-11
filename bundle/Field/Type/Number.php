<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field\Type;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Range;

class Number extends FieldType
{
    /**
     * @param array $properties
     *
     * @return Field
     */
    public function getEntity(array $properties = []): Field
    {
        return new Field\Number($properties);
    }

    /**
     * @param Field $field
     *
     * @return bool
     */
    public function accept(Field $field): bool
    {
        return $field instanceof Field\Number;
    }

    /**
     * @inheritDoc
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'min',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'novaformbuilder_field.number.min',
                    'empty_data' => 0,
                ]
            )
            ->add(
                'max',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'novaformbuilder_field.number.max',
                    'empty_data' => 0,
                ]
            );
    }

    /**
     * @param FormInterface $fieldForm
     * @param Field\Number $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                IntegerType::class,
                [
                    'required' => $field->isRequired(),
                    'label'    => $field->getName(),
                    'constraints' => [
                        new Range([
                            'min' => $field->getMin(),
                            'max' => $field->getMax()
                        ])
                    ]
                ]
            );
    }
}