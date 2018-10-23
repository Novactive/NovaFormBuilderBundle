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

namespace Novactive\Bundle\eZFormBuilderBundle\Listener;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;

/**
 * Class TopMenu.
 */
class TopMenu
{
    public function onMenuConfigure(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $item = $menu->addChild(
            'novaezformbuilder',
            [
                'label' => 'Nova eZ Form Builder',
            ]
        );

        $item->addChild(
            'novaezformbuilder_forms_list',
            [
                'label' => 'Forms',
                'route' => 'novaezformbuilder_dashboard_index',
            ]
        );

        $item->addChild(
            'novaezformbuilder_forms_submissions',
            [
                'label' => 'Submissions',
                'route' => 'novaezformbuilder_dashboard_submissions',
            ]
        );
    }
}
