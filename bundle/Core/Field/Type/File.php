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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints;

class File extends FieldType
{
    public const TYPE_IMAGE = 'image';

    public const TYPE_APPLICATION = 'file';

    public const TYPE_ALL = 'all';

    public const APPLICATION_MIME_TYPES = [
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/pdf',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];

    public function getEntityClass(): string
    {
        return Field\File::class;
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
                        'file.file_type.image'       => self::TYPE_IMAGE,
                        'file.file_type.application' => self::TYPE_APPLICATION,
                        'file.file_type.all'         => self::TYPE_ALL,
                    ],
                ]
            );
    }

    /**
     * @param Field\File $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $constraints = [];

        if (self::TYPE_IMAGE === $field->getFileType()) {
            $constraints[] = new Constraints\Image([
                'maxSize'   => $field->getMaxFileSizeMb().'M',
                'mimeTypes' => 'image/*',
            ]);
        }
        if (self::TYPE_APPLICATION === $field->getFileType()) {
            $constraints[] = new Constraints\File([
                'maxSize'   => $field->getMaxFileSizeMb().'M',
                'mimeTypes' => self::APPLICATION_MIME_TYPES,
            ]);
        }
        if (self::TYPE_ALL === $field->getFileType()) {
            $constraints[] = new Constraints\File([
                'maxSize'   => $field->getMaxFileSizeMb().'M',
            ]);
        }

        $fieldForm->add(
            'value',
            FileType::class,
            [
                'required'    => $field->isRequired(),
                'label'       => $field->getName(),
                'constraints' => $constraints,
            ]
        );
    }
}
