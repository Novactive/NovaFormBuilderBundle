<?php
/**
 * NovaFormBuilder package.
 *
 * @package   Novactive\Bundle\eZFormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\eZFormBuilderBundle;

use Novactive\Bundle\eZFormBuilderBundle\DependencyInjection\Security\Provider\PolicyProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NovaeZFormBuilderBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addPolicyProvider(new PolicyProvider());
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $extension = $this->createContainerExtension();
            if (null !== $extension) {
                if (!$extension instanceof ExtensionInterface) {
                    $exceptionMsg = sprintf(
                        'Extension %s must implement %s.',
                        \get_class($extension),
                        ExtensionInterface::class
                    );
                    throw new \LogicException($exceptionMsg);
                }
                $this->extension = $extension;
            } else {
                $this->extension = false;
            }
        }
        if ($this->extension) {
            return $this->extension;
        }
    }
}
