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
use EzSystems\EzPlatformAdminUi\Menu\MenuItemFactory;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TopMenu.
 */
class TopMenu
{
    /** @var TranslatorInterface */
    protected $translator;

    /** @var MenuItemFactory */
    protected $factory;

    /**
     * TopMenu constructor.
     */
    public function __construct(TranslatorInterface $translator, MenuItemFactory $factory)
    {
        $this->translator = $translator;
        $this->factory    = $factory;
    }

    public function onMenuConfigure(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();

        $item = $menu->addChild(
            $this->factory->createItem(
                'novaezformbuilder',
                [
                    'label' => $this->translator->trans('topmenu.tab.novaezformbuilder', [], 'novaezformbuilder'),
                ]
            )
        );

        $item->addChild(
            $this->factory->createItem(
                'novaezformbuilder_forms_list',
                [
                    'label'  => $this->translator->trans('topmenu.tab.forms', [], 'novaezformbuilder'),
                    'route'  => 'novaezformbuilder_dashboard_index',
                    'extras' => [
                        'routes' => [
                            'view'   => 'novaezformbuilder_dashboard_view',
                            'edit'   => 'novaezformbuilder_dashboard_edit',
                            'create' => 'novaezformbuilder_dashboard_create',
                            'delete' => 'novaezformbuilder_dashboard_delete',
                        ],
                    ],
                ]
            )
        );

        $item->addChild(
            $this->factory->createItem(
                'novaezformbuilder_forms_submissions',
                [
                    'label'  => $this->translator->trans('topmenu.tab.submissions', [], 'novaezformbuilder'),
                    'route'  => 'novaezformbuilder_dashboard_submissions',
                    'extras' => [
                        'routes' => [
                            'submissions' => 'novaezformbuilder_dashboard_submissions',
                            'submission'  => 'novaezformbuilder_dashboard_submission',
                        ],
                    ],
                ]
            )
        );
    }
}
