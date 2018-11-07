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

namespace Novactive\Bundle\eZFormBuilderBundle\Controller\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Novactive\Bundle\eZFormBuilderBundle\Core\FormService;
use Novactive\Bundle\FormBuilderBundle\Core\FormFactory;
use Novactive\Bundle\FormBuilderBundle\Core\Submitter;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class DashboardController
{
    public const RESULTS_PER_PAGE = 10;

    /**
     * Test action to render & handle clientside form.
     *
     * @Route("/view/{id}", name="novaezformbuilder_dashboard_view")
     * @Template("@ezdesign/novaezformbuilder/show.html.twig")
     */
    public function view(
        Form $formEntity,
        RouterInterface $router,
        Request $request,
        FormFactory $factory,
        Submitter $submitter
    ) {
        $form = $factory->createCollectForm($formEntity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $submitter->canSubmit($form, $formEntity)) {
            $submitter->createAndLogSubmission($formEntity);

            return new RedirectResponse($router->generate('novaezformbuilder_dashboard_index'));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/edit/{id}", name="novaezformbuilder_dashboard_edit")
     * @Route("/create", name="novaezformbuilder_dashboard_create")
     * @Template("@ezdesign/novaezformbuilder/edit.html.twig")
     */
    public function edit(
        ?Form $formEntity,
        RouterInterface $router,
        Request $request,
        FormFactory $factory,
        FormService $formService
    ) {
        $originalFields = new ArrayCollection();
        if (null === $formEntity) {
            $formEntity = new Form();
        } else {
            foreach ($formEntity->getFields() as $field) {
                $originalFields->add($field);
            }
        }

        $form = $factory->createEditForm($formEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formService->save($originalFields, $formEntity);

            return new RedirectResponse($router->generate('novaezformbuilder_dashboard_index'));
        }

        return [
            'title' => 'novaezformbuilder.title.edit_form',
            'form'  => $form->createView(),
        ];
    }

    /**
     * @Route("/render/{id}", name="novaezformbuilder_dashboard_render", defaults={"id"=null})
     * @Template("@ezdesign/novaezformbuilder/render.html.twig")
     */
    public function render(
        ?Form $formEntity,
        RouterInterface $router,
        Request $request,
        FormFactory $factory,
        FormService $formService
    ): array {
        $originalFields = new ArrayCollection();
        if (null === $formEntity) {
            $formEntity = new Form();
        } else {
            foreach ($formEntity->getFields() as $field) {
                $originalFields->add($field);
            }
        }

        $form = $factory->createEditForm($formEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formService->save($originalFields, $formEntity);

            return ['success' => true];
        }

        return [
            'title'       => 'novaezformbuilder.title.edit_form',
            'renderForm'  => $form->createView(),
        ];
    }

    /**
     * @Route("/submissions/{page}", name="novaezformbuilder_dashboard_submissions", requirements={"page" = "\d+"})
     * @Template("@ezdesign/novaezformbuilder/submissions.html.twig")
     */
    public function submissions(EntityManagerInterface $entityManager, int $page = 1): array
    {
        $queryBuilder = $entityManager->createQueryBuilder()->select('s')->from(FormSubmission::class, 's');

        $paginator = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $paginator->setMaxPerPage(self::RESULTS_PER_PAGE);
        $paginator->setCurrentPage($page);

        return [
            'totalCount'  => $paginator->getNbResults(),
            'submissions' => $paginator,
        ];
    }

    /**
     * @Route("/submission/{id}", name="novaezformbuilder_dashboard_submission")
     * @Template("@ezdesign/novaezformbuilder/submission.html.twig")
     */
    public function submission(FormSubmission $formSubmission): array
    {
        return [
            'submission' => $formSubmission,
        ];
    }

    /**
     * @Route("/{page}", name="novaezformbuilder_dashboard_index", requirements={"page" = "\d+"})
     * @Template("@ezdesign/novaezformbuilder/index.html.twig")
     */
    public function index(EntityManagerInterface $entityManager, int $page = 1): array
    {
        $queryBuilder = $entityManager->createQueryBuilder()->select('f')->from(Form::class, 'f');

        $paginator = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $paginator->setMaxPerPage(self::RESULTS_PER_PAGE);
        $paginator->setCurrentPage($page);

        return [
            'totalCount' => $paginator->getNbResults(),
            'forms'      => $paginator,
        ];
    }
}
