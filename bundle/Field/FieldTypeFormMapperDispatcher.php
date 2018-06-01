<?php
/**
 * @copyright Novactive
 * Date: 01/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\FormInterface;

class FieldTypeFormMapperDispatcher implements FieldTypeFormMapperDispatcherInterface
{
    /**
     * Field form mappers, indexed by type.
     *
     * @var FieldFormMapperInterface[]
     */
    private $mappers = [];

    /**
     * FieldTypeFormMapperDispatcher constructor.
     *
     * @param FieldFormMapperInterface[]|iterable $mappers
     */
    public function __construct(iterable $mappers)
    {
        foreach ($mappers as $mapper) {
            $this->addMapper($mapper);
        }
    }

    /**
     * @inheritDoc
     */
    public function addMapper(FieldFormMapperInterface $mapper)
    {
        $this->mappers[$mapper->getFieldType()] = $mapper;
    }

    /**
     * @inheritDoc
     */
    public function mapFieldEditForm(FormInterface $fieldForm, Field $field)
    {
        $fieldType = get_class($field);
        if (isset($this->mappers[$fieldType])) {
            $this->mappers[$fieldType]->mapFieldEditForm($fieldForm, $field);
        }
    }

    /**
     * @inheritDoc
     */
    public function mapFieldCollectForm(FormInterface $fieldForm, Field $field)
    {
        $fieldType = get_class($field);
        if (isset($this->mappers[$fieldType])) {
            $this->mappers[$fieldType]->mapFieldCollectForm($fieldForm, $field);
        }
    }
}
