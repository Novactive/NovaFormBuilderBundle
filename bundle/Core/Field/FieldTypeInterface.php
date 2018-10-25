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

namespace Novactive\Bundle\FormBuilderBundle\Core\Field;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;

interface FieldTypeInterface
{
    public function getEntityClass(): string;

    public function getIdentifier(): string;

    public function supportsEntity(Field $field): bool;

    public function newEntity(array $properties = []): Field;
}
