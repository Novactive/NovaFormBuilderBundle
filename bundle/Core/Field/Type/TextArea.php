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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;

class TextArea extends FieldType
{
    public function getEntityClass(): string
    {
        return Field\TextArea::class;
    }

    /**
     * @param Field\TextArea $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'minLength',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'field.textarea.min_length',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            )
            ->add(
                'maxLength',
                IntegerType::class,
                [
                    'required'   => false,
                    'label'      => 'field.textarea.max_length',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            );
    }

    /**
     * @param Field\TextArea $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                TextareaType::class,
                [
                    'required'    => $field->isRequired(),
                    'label'       => $field->getName(),
                    'constraints' => [
                        new Length(
                            [
                                'min' => $field->getMinLength(),
                                'max' => $field->getMaxLength(),
                            ]
                        ),
                    ],
                ]
            );
    }
}
