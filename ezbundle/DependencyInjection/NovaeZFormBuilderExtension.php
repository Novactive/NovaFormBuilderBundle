<?php
/**
 * NovaFormBuilder package.
 *
 * @package   Novactive\Bundle\eZFormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\eZFormBuilderBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class NovaeZFormBuilderExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $config1 = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/ez_field_templates.yml'));
        $container->prependExtensionConfig('ezpublish', $config1);

        $config2 = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/ez_admin_edit_field.yml'));
        $container->prependExtensionConfig('twig', $config2);
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('ezadminui.yaml');
        $loader->load('services.yaml');
        $loader->load('fieldtypes.yml');
        $asseticBundles   = $container->getParameter('assetic.bundles');
        $asseticBundles[] = 'NovaeZFormBuilderBundle';
        $container->setParameter('assetic.bundles', $asseticBundles);
    }
}
