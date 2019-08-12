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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;

class Paragraph extends FieldType
{
    public function getEntityClass(): string
    {
        return Field\Paragraph::class;
    }

    /**
     * @param Field\Paragraph $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                TextareaType::class,
                [
                    'required'   => false,
                    'label'      => 'field.paragraph.data',
                    'attr'       => ['rows' => 10],
                    'data'       => $field->getValue()

                ]
            );
    }

    /**
     * @param Field\Paragraph $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        var_dump($field);die();

        $fieldForm
            ->add(
                'value',
                Field\Paragraph::class,
                [
                    'required'    => $field->isRequired(),
                    'label'       => $field->getValue(),
                    'options'      => ['value' => $field->getOption('data')]
                ]
            );
    }
}
