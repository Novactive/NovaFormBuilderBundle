<?php
/**
 * @copyright Novactive
 * Date: 01/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\FormInterface;

interface FieldTypeFormMapperDispatcherInterface
{
    /**
     * Adds a new Field mapper for a type.
     *
     * @param FieldFormMapperInterface
     *
     * @return mixed
     */
    public function addMapper(FieldFormMapperInterface $mapper);

    /**
     * @param FormInterface $fieldForm
     * @param Field         $field
     *
     * @return mixed
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field);

    /**
     * @param FormInterface $fieldForm
     * @param Field         $field
     *
     * @return mixed
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field);
}
