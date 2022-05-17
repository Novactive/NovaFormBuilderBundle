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

use Novactive\Bundle\FormBuilderBundle\Core\Field\FieldType;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Range;

class Number extends FieldType
{
    public function getEntityClass(): string
    {
        return Field\Number::class;
    }

    /**
     * @param Field\Number $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'placeholder',
                TextType::class,
                [
                    'label'    => 'field.placeholder',
                    'required' => false,
                ]
            )
            ->add(
                'min',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'field.number.min',
                    'empty_data' => 0,
                ]
            )
            ->add(
                'max',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'field.number.max',
                    'empty_data' => 100,
                ]
            )
            ->add(
                'identifier',
                TextType::class,
                [
                    'required' => false,
                    'label'    => 'field.identifier',
                ]
            );
    }

    /**
     * @param Field\Number $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $identifier  = $field->getIdentifier() ?: null;
        $min         = $field->getMin() ?: null;
        $max         = $field->getMax() ?: null;
        $placeholder = $field->getPlaceholder() ?: null;
        $attributes  = [];
        $constraints = [];

        if (null !== $min) {
            $attributes['min'] = $min;
        }
        if (null !== $max) {
            $attributes['max'] = $max;
        }
        if (null !== $min || null !== $max) {
            $constraints[] = new Range(
                [
                    'min' => $min,
                    'max' => $max,
                ]
            );
        }
        if (null !== $placeholder) {
            $attributes['placeholder'] = $placeholder;
        }
        if (null !== $identifier) {
            $attributes['data-identifier'] = $identifier;
        }

        $fieldForm
            ->add(
                'value',
                IntegerType::class,
                [
                    'required'    => $field->isRequired(),
                    'label'       => $field->getName(),
                    'attr'        => $attributes,
                    'constraints' => $constraints,
                ]
            );
    }
}
