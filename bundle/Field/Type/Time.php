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
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Range;

class Time extends FieldType
{
    public function getEntity(array $properties = []): Field
    {
        return new Field\Time($properties);
    }

    public function supportsEntity(Field $field): bool
    {
        return $field instanceof Field\Time;
    }

    /**
     * @param Field\Time $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
    }

    /**
     * @param Field\Time $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                TimeType::class,
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
