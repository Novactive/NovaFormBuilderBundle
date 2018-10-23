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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints;

class File extends FieldType
{
    const TYPE_IMAGE = 'image';
    const TYPE_FILE  = 'file';

    public function getEntity(array $properties = []): Field
    {
        return new Field\File($properties);
    }

    public function supportsEntity(Field $field): bool
    {
        return $field instanceof Field\File;
    }

    /**
     * @param Field\File $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'maxFileSizeMb',
                NumberType::class,
                [
                    'required'   => false,
                    'label'      => 'field.file.max_file_size_mb',
                    'attr'       => ['min' => 0],
                    'empty_data' => 0,
                ]
            )
            ->add(
                'fileType',
                ChoiceType::class,
                [
                    'required' => true,
                    'label'    => 'field.file.file_type',
                    'choices'  => [
                        'file.file_type.image' => self::TYPE_IMAGE,
                        'file.file_type.file'  => self::TYPE_FILE,
                    ],
                ]
            );
    }

    /**
     * @param Field\File $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm->add(
            'value',
            FileType::class,
            [
                'required'    => $field->isRequired(),
                'label'       => $field->getName(),
                'constraints' => [
                    new Constraints\File(
                        [
                            'maxSize'   => $field->getMaxFileSizeMb().'M',
                            'mimeTypes' => $field->getFileType(),
                        ]
                    ),
                ],
            ]
        );
    }
}
