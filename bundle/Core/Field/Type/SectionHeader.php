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
use Novactive\Bundle\FormBuilderBundle\Form\Type\SectionHeaderType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;

class SectionHeader extends FieldType
{
    public function getEntityClass(): string
    {
        return Field\SectionHeader::class;
    }

    /**
     * @param Field\SectionHeader $field
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                TextType::class,
                [
                    'required'   => false,
                    'label'      => 'field.section.data',
                    'attr'       => ['rows' => 10],
                    'data'       => $field->getValue(),
                ]
            );
    }

    /**
     * @param Field\SectionHeader $field
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void
    {
        $fieldForm
            ->add(
                'value',
                SectionHeaderType::class,
                [
                    'label'       => $field->getValue(),
                    'data'        => $field->getOption('data'),
                ]
            );
    }

    public function canExport(): bool
    {
        return false;
    }
}
