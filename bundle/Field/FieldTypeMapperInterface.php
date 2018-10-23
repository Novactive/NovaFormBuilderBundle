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

namespace Novactive\Bundle\FormBuilderBundle\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\FormInterface;

interface FieldTypeMapperInterface
{
    /**
     * Tell if the field need to be mapped by this mapper.
     */
    public function supportsEntity(Field $field): bool;

    /**
     * Add custom fields to the FieldEditType form.
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void;

    /**
     * Add custom fields to the FieldCollectType form.
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void;
}
