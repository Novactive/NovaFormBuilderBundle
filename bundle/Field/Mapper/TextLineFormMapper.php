<?php
/**
 * @copyright Novactive
 * Date: 01/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field\Mapper;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldFormMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormInterface;

class TextLineFormMapper implements FieldFormMapperInterface
{
    public function getFieldType(): string
    {
        return Field\TextLine::class;
    }

    /**
     * @inheritDoc
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'minLength',
                IntegerType::class,
                [
                    'required' => false,
                    'label'    => 'novaformbuilder_field.textline.min_length',
                    'attr'     => ['min' => 0],
                ]
            )
            ->add(
                'maxLength',
                IntegerType::class,
                [
                    'required' => false,
                    'label'    => 'novaformbuilder_field.textline.max_length',
                    'attr'     => ['min' => 0],
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
