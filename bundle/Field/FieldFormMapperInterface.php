<?php
/**
 * @copyright Novactive
 * Date: 01/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\FormInterface;

interface FieldFormMapperInterface
{
    /**
     * Tell if the field need to be mapped by this mapper.
     *
     * @param Field $field
     *
     * @return bool
     */
    public function accept(Field $field): bool;

    /**
     * Add custom fields to the FieldEditType form.
     *
     * @param FormInterface $fieldForm
     * @param Field         $field
     *
     * @return mixed
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void;

    /**
     * Add custom fields to the FieldCollectType form.
     *
     * @param FormInterface $fieldForm
     * @param Field         $field
     *
     * @return mixed
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void;
}
