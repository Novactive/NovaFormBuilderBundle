<?php
/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <r.limouzin@novactive.com>
 * @copyright 2020 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Core\Field\Type;

use Novactive\Bundle\FormBuilderBundle\Core\Field\FieldType;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormInterface;

class Url extends FieldType
{
    public function getEntityClass(): string
    {
        return Field\Url::class;
    }

    /**
     * @param Field\Url $field
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
                'identifier',
                TextType::class,
                [
                    'required' => false,
                    'label'    => 'field.identifier',
                ]
            );
        $choices = [
            'off'                                         => 'autocomplete.off',
            'on'                                          => 'autocomplete.on',
            'url'                                         => 'autocomplete.url',
        ];

        $fieldForm->add(
            'autoComplete',
            ChoiceType::class,
            [
                'label'    => 'field.autoComplete',
                'choices'  => array_flip($choices),
                'required' => false,
            ]
        );
    }

    /**
     * @param Field\Url $field
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
                UrlType::class,
                [
                    'required' => $field->isRequired(),
                    'label'    => $field->getName(),
                    'attr'     => $attributes,
                ]
            );
    }
}
