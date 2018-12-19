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
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TopMenu.
 */
class TopMenu
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function onMenuConfigure(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $item = $menu->addChild(
            'novaezformbuilder',
            [
                'label' => $this->translator->trans('topmenu.tab.novaezformbuilder', [], 'novaezformbuilder'),
            ]
        );

        $item->addChild(
            'novaezformbuilder_forms_list',
            [
                'label' => $this->translator->trans('topmenu.tab.forms', [], 'novaezformbuilder'),
                'route' => 'novaezformbuilder_dashboard_index',
            ]
        );

        $item->addChild(
            'novaezformbuilder_forms_submissions',
            [
                'label' => $this->translator->trans('topmenu.tab.submissions', [], 'novaezformbuilder'),
                'route' => 'novaezformbuilder_dashboard_submissions',
            ]
        );
    }
}
