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
            );
    }

    /**
     * @param Field\Url $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $placeholder = $field->getPlaceholder() ?: null;
        $attributes  = [];

        if (null !== $placeholder) {
            $attributes['placeholder'] = $placeholder;
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
