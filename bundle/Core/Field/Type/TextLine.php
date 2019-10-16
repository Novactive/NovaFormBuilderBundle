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
use Symfony\Component\Validator\Constraints\Length;

class TextLine extends FieldType
{
    public function getEntityClass(): string
    {
        return Field\TextLine::class;
    }

    /**
     * @param Field\TextLine $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'defaultValue',
                TextType::class,
                [
                    'required'   => false,
                    'label'      => 'field.textline.default_value',
                ]
            )
            ->add(
                'minLength',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'field.textline.min_length',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            )
            ->add(
                'maxLength',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'field.textline.max_length',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            );
    }

    /**
     * @param Field\TextLine $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $minLength   = $field->getMinLength() ?: null;
        $maxLength   = $field->getMaxLength() ?: null;
        $constraints = [];
        if (null !== $minLength || null !== $maxLength) {
            $constraints[] = new Length(
                [
                    'min' => $minLength,
                    'max' => $maxLength,
                ]
            );
        }

        $fieldForm
            ->add(
                'value',
                TextType::class,
                [
                    'required'    => $field->isRequired(),
                    'label'       => $field->getName(),
                    'constraints' => $constraints,
                ]
            );
    }
}
