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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Range;

class Number extends FieldType
{
    public function getEntity(array $properties = []): Field
    {
        return new Field\Number($properties);
    }

    public function supports(Field $field): bool
    {
        return $field instanceof Field\Number;
    }

    /**
     * @param Field\Number $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'min',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'novaformbuilder.field.number.min',
                    'empty_data' => 0,
                ]
            )
            ->add(
                'max',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'novaformbuilder.field.number.max',
                    'empty_data' => 0,
                ]
            );
    }

    /**
     * @param Field\Number $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                IntegerType::class,
                [
                    'required'    => $field->isRequired(),
                    'label'       => $field->getName(),
                    'constraints' => [
                        new Range(
                            [
                                'min' => $field->getMin(),
                                'max' => $field->getMax(),
                            ]
                        ),
                    ],
                ]
            );
    }
}
