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
    public function getFieldType(): string;

    /**
     * @param FormInterface $fieldForm
     * @param Field         $field
     *
     * @return mixed
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field): void;

    /**
     * @param FormInterface $fieldForm
     * @param Field         $field
     *
     * @return mixed
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field): void;
}
