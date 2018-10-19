<?php

namespace Novactive\Bundle\FormBuilderBundle\Field\Type;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Novactive\Bundle\FormBuilderBundle\Field\FieldType;
use Symfony\Component\Form\FormInterface;

class Email extends FieldType
{
    /**
     * @param array $properties
     *
     * @return Field
     */
    public function getEntity(array $properties = []): Field
    {
        return new Field\Email($properties);
    }

    /**
     * @param Field $field
     *
     * @return bool
     */
    public function accept(Field $field): bool
    {
        return $field instanceof Field\Email;
    }

    /**
     * @param FormInterface $fieldForm
     * @param Field         $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        // TODO: Implement mapFieldEditForm() method.
    }

    /**
     * @param FormInterface $fieldForm
     * @param Field         $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                EmailType::class,
                [
                    'required' => $field->isRequired(),
                    'label'    => $field->getName(),
                ]
            );
    }
}
