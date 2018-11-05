<?php

/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Maxim Strukov <m.strukov@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

namespace Novactive\Bundle\eZFormBuilderBundle\Form;

use eZ\Publish\API\Repository\FieldType;
use eZ\Publish\Core\FieldType\Value;
use Symfony\Component\Form\DataTransformerInterface;

class CustomFormValueTransformer implements DataTransformerInterface
{
    /**
     * @var FieldType
     */
    private $fieldType;

    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    public function transform($value)
    {
        if (!$value instanceof Value) {
            return null;
        }

        return (string) $value;
    }

    public function reverseTransform($value)
    {
        return $this->fieldType->fromHash($value);
    }
}
