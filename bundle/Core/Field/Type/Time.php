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
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormInterface;

class Time extends FieldType
{
    public function getEntityClass(): string
    {
        return Field\Time::class;
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
                    'required' => $field->isRequired(),
                    'label'    => $field->getName(),
                ]
            );
    }
}
