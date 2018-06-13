<?php
/**
 * NovaFormBuilderBundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE
 */

namespace Novactive\Bundle\FormBuilderBundle;

use Novactive\Bundle\FormBuilderBundle\Field\FieldType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FormBuilderBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->registerForAutoconfiguration(FieldType::class)
            ->addTag('novaformbuilder.fieldtype');
    }
}
