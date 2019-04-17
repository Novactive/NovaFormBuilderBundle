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

declare(strict_types=1);

namespace Novactive\Bundle\eZFormBuilderBundle\Controller\Front;

use Novactive\Bundle\FormBuilderBundle\Core\FormFactory;
use Novactive\Bundle\FormBuilderBundle\Core\Submitter;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormController
{
    /**
     * @var string
     */
    private $pageLayout;

    /**
     * @required
     */
    public function setPagelayout(string $pagelayout): void
    {
        $this->pageLayout = $pagelayout;
    }

    /**
     * Render & handle clientside form on Front Office.
     *
     * @Route("/showfront/{id}", name="novaezformbuilder_front_show_form")
     * @Template("@ezdesign/fields/ezcustomform_show_front.html.twig")
     */
    public function show(
        Form $formEntity,
        Request $request,
        FormFactory $factory,
        Submitter $submitter
    ): array {
        $form = $factory->createCollectForm($formEntity);

        $form->handleRequest($request);

        $success = false;
        if ($form->isSubmitted() && $form->isValid() && $submitter->canSubmit($form, $formEntity)) {
            $submitter->createAndLogSubmission($formEntity);
            $success = true;
        }
        $stepBack = $request->request->get('stepBack') ?? 0;

        return [
            'pagelayout' => $this->pageLayout,
            'form'       => $form->createView(),
            'success'    => $success,
            'stepBack'   => $stepBack,
            'canSubmit'  => $submitter->canSubmit($form, $formEntity),
        ];
    }
}
