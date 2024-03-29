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
use Novactive\Bundle\FormBuilderBundle\Core\Field\Type\Compose\AutoComplete;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;

class TextArea extends FieldType
{
    use AutoComplete;

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
                'defaultValue',
                TextareaType::class,
                [
                    'required'   => false,
                    'label'      => 'field.textarea.default_value',
                ]
            )
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
        $this->addAutoCompleteField($fieldForm);
    }

    /**
     * @param Field\TextArea $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $minLength    = $field->getMinLength() ?: null;
        $maxLength    = $field->getMaxLength() ?: null;
        $autoComplete = $field->getAutoComplete() ?: null;

        $attributes = [];
        if (null !== $minLength) {
            $attributes['minlength'] = $minLength;
        }
        if (null !== $maxLength) {
            $attributes['maxlength'] = $maxLength;
        }
        if (null !== $autoComplete) {
            $attributes['autocomplete'] = $autoComplete;
        }

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
                TextareaType::class,
                [
                    'required'    => $field->isRequired(),
                    'label'       => $field->getName(),
                    'constraints' => $constraints,
                    'attr'        => $attributes,
                ]
            );
    }
}
