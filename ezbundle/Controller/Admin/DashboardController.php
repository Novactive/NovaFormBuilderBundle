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

use Doctrine\ORM\EntityManagerInterface;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use eZ\Publish\Core\Repository\Permission\PermissionResolver;
use Novactive\Bundle\eZFormBuilderBundle\Core\FormService;
use Novactive\Bundle\eZFormBuilderBundle\Core\FormSubmissionService;
use Novactive\Bundle\eZFormBuilderBundle\Form\Type\SubmissionsFilterType;
use Novactive\Bundle\FormBuilderBundle\Core\FileUploaderInterface;
use Novactive\Bundle\FormBuilderBundle\Core\FormFactory;
use Novactive\Bundle\FormBuilderBundle\Core\Submitter;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactory as SymfonyFormFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

            return new RedirectResponse(
                $router->generate('novaezformbuilder_dashboard_submissions', ['id' => $formEntity->getId()])
            );
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
        if (null === $formEntity) {
            $formEntity = new Form();
        }
        $form = $factory->createEditForm($formEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (\array_key_exists('submissionsUnlimited', $request->request->get('novaformbuilder_form_edit'))) {
                $formEntity->setMaxSubmissions(null);
            }
            $sendData = $request->request->get('novaformbuilder_form_edit')['sendData'] ?? false;

            $formEntity->setSendData((bool) $sendData);
            $formService->save($formEntity);

            return new RedirectResponse($router->generate('novaezformbuilder_dashboard_index'));
        }
        if (null === $form->get('maxSubmissions')->getData()) {
            $form->get('maxSubmissions')->setData(0);
            $form->get('submissionsUnlimited')->setData(true);
        }

        return [
            'title' => 'novaezformbuilder.title.edit_form',
            'form'  => $form->createView(),
        ];
    }

    /**
     * @Route("/editmodal/{id}", name="novaezformbuilder_dashboard_edit_modal", defaults={"id"=null})
     * @Template("@ezdesign/novaezformbuilder/edit_modal.html.twig")
     */
    public function editModal(
        ?Form $formEntity,
        Request $request,
        FormFactory $factory,
        FormService $formService
    ) {
        if (null === $formEntity) {
            $formEntity = new Form();
        }
        $form = $factory->createEditForm($formEntity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (\array_key_exists('submissionsUnlimited', $request->request->get('novaformbuilder_form_edit'))) {
                $formEntity->setMaxSubmissions(null);
            }
            $sendData = $request->request->get('novaformbuilder_form_edit')['sendData'] ?? false;

            $formEntity->setSendData((bool) $sendData);
            $formId = $formService->save($formEntity);

            return (new JsonResponse())->setContent(
                json_encode(['success' => true, 'id' => $formId, 'name' => $formEntity->getName()])
            );
        }
        if (null === $form->get('maxSubmissions')->getData()) {
            $form->get('maxSubmissions')->setData(0);
            $form->get('submissionsUnlimited')->setData(true);
        }
        if (null !== $form->get('receiverEmail')->getData()) {
            $form->get('sendData')->setData(true);
        }

        return [
            'title'      => 'novaezformbuilder.title.edit_form',
            'renderForm' => $form->createView(),
        ];
    }

    /**
     * @Route("/delete/{id}", name="novaezformbuilder_dashboard_delete")
     */
    public function delete(Form $formEntity, FormService $formService, RouterInterface $router): RedirectResponse
    {
        $formService->removeForm($formEntity);

        return new RedirectResponse($router->generate('novaezformbuilder_dashboard_index'));
    }

    /**
     * @Route("/remove/modal/{id}", name="novaezformbuilder_dashboard_remove_modal")
     */
    public function removeModal(Form $formEntity, FormService $formService): JsonResponse
    {
        $formService->removeForm($formEntity);

        return (new JsonResponse())->setContent(json_encode(['success' => true]));
    }

    /**
     * @Route("/submissions/{id}", name="novaezformbuilder_dashboard_submissions")
     * @Template("@ezdesign/novaezformbuilder/submissions.html.twig")
     */
    public function submissions(
        EntityManagerInterface $entityManager,
        ?Form $form,
        Request $request,
        FormService $formService,
        PermissionResolver $permissionResolver,
        SymfonyFormFactory $formFactory
    ): array {
        $page = $request->query->get('page') ?? 1;

        $queryBuilder = $entityManager->createQueryBuilder()->select('s')->from(FormSubmission::class, 's');
        if (null !== $form) {
            $associatedContents = $formService->associatedContents($form);
            foreach ($associatedContents as $associatedContent) {
                if (!$permissionResolver->canUser('form', 'read_submissions', $associatedContent)) {
                    throw new UnauthorizedException('form', 'read_submissions', ['formId' => $form->getId()]);
                }
            }

            $queryBuilder->andWhere($queryBuilder->expr()->eq('s.form', ':value'))->setParameter(
                'value',
                $form
            );
            $queryBuilder->orderBy('s.createdAt', 'DESC');
        }

        $paginator = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $paginator->setMaxPerPage(self::RESULTS_PER_PAGE);
        $paginator->setCurrentPage($page);

        $submissionsFilterForm = $formFactory->create(SubmissionsFilterType::class);
        $submissionsFilterForm->setData(['form' => $form->getId()]);

        return [
            'totalCount'            => $paginator->getNbResults(),
            'submissions'           => $paginator,
            'form'                  => $form,
            'submissionsFilterType' => $submissionsFilterForm->createView(),
        ];
    }

    /**
     * @Route("/submission/{id}", name="novaezformbuilder_dashboard_submission")
     * @Template("@ezdesign/novaezformbuilder/submission.html.twig")
     */
    public function submission(
        FormSubmission $formSubmission,
        FormService $formService,
        PermissionResolver $permissionResolver,
        FormSubmissionService $formSubmissionService
    ): array {
        $form               = $formSubmission->getForm();
        $associatedContents = $formService->associatedContents($form);
        foreach ($associatedContents as $associatedContent) {
            if (!$permissionResolver->canUser('form', 'read_submissions', $associatedContent)) {
                throw new UnauthorizedException('form', 'read_submissions', ['formId' => $form->getId()]);
            }
        }

        return [
            'form'             => $form,
            'submission'       => $formSubmission,
            'exportable_datas' => $formSubmissionService->getExportableDatas($formSubmission),
        ];
    }

    /**
     * @Route("/submissions/download/{id}/{type}", name="novaezformbuilder_dashboard_submissions_download")
     */
    public function downloadSubmissions(Form $form, string $type, FormService $formService): Response
    {
        $file = $formService->generateSubmissions($form, $type);

        $response = new BinaryFileResponse($file);
        $response->deleteFileAfterSend(true);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf(
                'form-%s-submissions.%s',
                $form->getId(),
                $file->getExtension()
            )
        );

        return $response;
    }

    /**
     * @Route("/submissions/download/filtered", name="novaezformbuilder_dashboard_submissions_filter_download")
     */
    public function downloadFilteredSubmissions(
        Request $request,
        FormService $formService,
        SymfonyFormFactory $formFactory
    ): Response {
        $submissionsFilterForm = $formFactory->create(SubmissionsFilterType::class);
        $submissionsFilterForm->handleRequest($request);
        $filter = $submissionsFilterForm->getData();
        $file   = $formService->generateSubmissionsByFilter($filter);

        $response = new BinaryFileResponse($file);
        $response->deleteFileAfterSend(true);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf(
                'form-%s-submissions.%s',
                $filter['form'],
                $file->getExtension()
            )
        );

        return $response;
    }

    /**
     * @Route("/submission/file/download/{id}", name="novaezformbuilder_dashboard_submission_file_download")
     */
    public function downloadSubmissionFile(
        FormSubmission $formSubmission,
        FileUploaderInterface $fileUploader
    ): Response {
        /* @var Field $field */
        foreach ($formSubmission->getData() as $field) {
            if ('file' === $field['type']) {
                return $fileUploader->getFile($field['value']);
            }
        }

        return new Response('File is not available.');
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
