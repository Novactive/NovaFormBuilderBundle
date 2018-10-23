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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;

class Date extends FieldType
{
    public function getEntity(array $properties = []): Field
    {
        return new Field\Date($properties);
    }

    public function supports(Field $field): bool
    {
        return $field instanceof Field\Date;
    }

    /**
     * @param Field\Date $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'defaultValue',
                ChoiceType::class,
                [
                    'required' => true,
                    'label'    => 'novaformbuilder.field.date.default_value',
                    'choices'  => ['empty', 'current date'],
                ]
            );
    }

    /**
     * @param Field\Date $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                DateType::class,
                [
                    'required' => $field->isRequired(),
                    'label'    => $field->getName(),
                ]
            );
    }
}
