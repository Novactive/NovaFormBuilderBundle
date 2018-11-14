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

namespace Novactive\Bundle\eZFormBuilderBundle\eZ\Publish\FieldType\CustomForm;

use eZ\Publish\Core\FieldType\Value as BaseValue;

class Value extends BaseValue
{
    /**
     * The Id of the Custom Form selected.
     *
     * @var int
     */
    public $formId;

    public function __construct($arg = [])
    {
        if (!\is_array($arg)) {
            $arg = ['formId' => $arg];
        }
        parent::__construct($arg);
    }

    public function __toString()
    {
        return (string) $this->formId;
    }
}
