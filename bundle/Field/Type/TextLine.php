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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;

class TextLine extends FieldType
{
    public function getEntity(array $properties = []): Field
    {
        return new Field\TextLine($properties);
    }

    public function supportsEntity(Field $field): bool
    {
        return $field instanceof Field\TextLine;
    }

    /**
     * @param Field\TextLine $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
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
        $fieldForm
            ->add(
                'value',
                TextType::class,
                [
                    'required'    => $field->isRequired(),
                    'label'       => 'field.textline.value',
                    'attr'        => [
                        'min' => $field->getMinLength(),
                        'max' => $field->getMaxLength(),
                    ],
                    'empty_data'  => 0,
                    'constraints' => [
                        new Length(
                            [
                                'min' => $field->getMinLength() ?: null,
                                'max' => $field->getMaxLength() ?: null,
                            ]
                        ),
                    ],
                ]
            );
    }
}
