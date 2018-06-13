<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field\Type;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;

class Date extends FieldType
{
    public function getEntity(): Field
    {
        return new Field\Date();
    }

    /**
     * @param Field $field
     *
     * @return bool
     */
    public function accept(Field $field): bool
    {
        return $field instanceof Field\Date;
    }

    /**
     * @inheritDoc
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'defaultValue',
                ChoiceType::class,
                [
                    'required' => false,
                    'label'    => 'novaformbuilder_field.date.default_value',
                    'choices'  => ['empty', 'current date'],
                ]
            );
    }

    /**
     * @inheritDoc
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        // TODO: Implement mapFieldCollectForm() method.
    }
}
