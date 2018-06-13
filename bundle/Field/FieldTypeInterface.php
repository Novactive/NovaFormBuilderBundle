<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;

interface FieldTypeInterface
{
    public function getEntity(): Field;

    public function getIdentifier(): string;
}
