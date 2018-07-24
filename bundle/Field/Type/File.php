<?php

namespace Novactive\Bundle\FormBuilderBundle\Field\Type;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use \Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\FormInterface;

class File extends FieldType
{
    const TYPE_IMAGE = 'image';
    const TYPE_FILE = 'file';

    /**
     * @param array $properties
     * @return Field
     */
    public function getEntity(array $properties = []): Field
    {
        return new Field\File($properties);
    }

    /**
     * @param Field $field
     * @return bool
     */
    public function accept(Field $field): bool
    {
        return $field instanceof Field\File;
    }

    /**
     * @param FormInterface $fieldForm
     * @param Field $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'maxFileSizeMb',
                NumberType::class,
                [
                    'required'   => false,
                    'label'      => 'novaformbuilder_field.file.max_file_size_mb',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            )
            ->add(
                'fileType',
                ChoiceType::class,
                [
                    'required' => true,
                    'label' => 'novaformbuilder_field.file.file_type',
                    'choices' => [
                        'novaformbuilder_field.file.file_type.image' => self::TYPE_IMAGE,
                        'novaformbuilder_field.file.file_type.file'  => self::TYPE_FILE,
                    ]
                ]
            )
        ;
    }

    /**
     * @param FormInterface $fieldForm
     * @param Field\File $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm->add('value', FileType::class,
            [
                'required' => $field->isRequired(),
                'label'    => $field->getName(),
                'constraints' => [
                    new Constraints\File([
                        'maxSize' => $field->getMaxFileSizeMb() . 'M',
                        'mimeTypes' => $field->getFileType()
                    ])
                ]
            ]);
    }

}