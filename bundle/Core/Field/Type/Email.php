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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;

class Email extends FieldType
{
    public function getEntityClass(): string
    {
        return Field\Email::class;
    }

    /**
     * @param Field\Email $field
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
                'sendData',
                CheckboxType::class,
                [
                    'label'    => 'form.send_data_to_user',
                    'required' => false,
                ]
            );
        $choices = [
            'off'                                         => 'autocomplete.off',
            'on'                                          => 'autocomplete.on',
            'email'                                       => 'autocomplete.email',
        ];

        $fieldForm->add(
            'autoComplete',
            ChoiceType::class,
            [
                'label'    => 'field.autoComplete',
                'choices'  => array_flip($choices),
                'required' => false,
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
     * @param Field\Email $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $identifier   = $field->getIdentifier() ?: null;
        $placeholder  = $field->getPlaceholder() ?: null;
        $autoComplete = $field->getAutoComplete() ?: null;
        $attributes   = [];

        if (null !== $placeholder) {
            $attributes['placeholder'] = $placeholder;
        }
        if (null !== $autoComplete) {
            $attributes['autocomplete'] = $autoComplete;
        }
        if (null !== $identifier) {
            $attributes['data-identifier'] = $identifier;
        }

        $fieldForm
            ->add(
                'value',
                EmailType::class,
                [
                    'required' => $field->isRequired(),
                    'label'    => $field->getName(),
                    'attr'     => $attributes,
                ]
            );
    }
}
