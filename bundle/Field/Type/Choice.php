<?php


namespace Novactive\Bundle\FormBuilderBundle\Field\Type;


use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldType;
use Novactive\Bundle\FormBuilderBundle\Form\Type\ChoiceCollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;

class Choice extends FieldType
{
    /**
     * @param array $properties
     *
     * @return Field
     */
    public function getEntity(array $properties = []): Field
    {
        return new Field\Choice($properties);
    }

    /**
     * @param Field $field
     *
     * @return bool
     */
    public function accept(Field $field): bool
    {
        return $field instanceof Field\Choice;
    }

    /**
     * @inheritDoc
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'choices',
                ChoiceCollectionType::class,
                [
                    'entry_type' => TextType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => true,
                    'label'    => 'novaformbuilder_field.choice.choices',
                    'empty_data' => []
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