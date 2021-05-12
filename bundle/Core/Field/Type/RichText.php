<?php
/**
 * @copyright Novactive
 * Date: 30/04/2021
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Core\Field\Type;


use EzSystems\EzPlatformRichText\Form\Type\RichTextType;
use Novactive\Bundle\FormBuilderBundle\Core\Field\FieldType;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Form\Type\ParagraphType;
use Symfony\Component\Form\FormInterface;

class RichText extends FieldType
{
    public function getEntityClass(): string
    {
        return Field\RichText::class;
    }

    /**
     * @param Field\RichText $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                RichTextType::class,
                [
                    'required'   => false,
                    'label'      => 'field.richtext.data',
                    'attr'       => ['rows' => 10],
                    'data'       => $field->getOption('value'),
                ]
            );
    }

    /**
     * @param Field\RichText $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                ParagraphType::class,
                [
                    'label'       => $field->getValue(),
                    'data'        => $field->getOption('value'),
                ]
            );
    }

    public function canExport(): bool
    {
        return false;
    }
}
