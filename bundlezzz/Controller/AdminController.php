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

namespace Novactive\Bundle\bundlezzz\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Novactive\Bundle\bundlezzz\Entity\Form;
use Novactive\Bundle\bundlezzz\Service\BuilderFormFactory;
use Novactive\Bundle\bundlezzz\Service\FormConstructor;
use Novactive\Bundle\bundlezzz\Service\FormSubmissionHelper;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController.
 *
 * @Route("/form-builder/admin")
 *
 * @package Novactive\Bundle\FormBuilderBundle\Controller
 */
class AdminController extends Controller
{
    /** @var BuilderFormFactory */
    protected $builderFormFactory;

    /**
     * @var FormConstructor
     */
    protected $formConstructor;

    /**
     * @var FormSubmissionHelper
     */
    protected $formSubmissionHelper;

    /**
     * AdminController constructor.
     */
    public function __construct(
        BuilderFormFactory $builderFormFactory,
        FormConstructor $formConstructor,
        FormSubmissionHelper $formSubmissionHelper
    ) {
        $this->builderFormFactory   = $builderFormFactory;
        $this->formConstructor      = $formConstructor;
        $this->formSubmissionHelper = $formSubmissionHelper;
    }

    const RESULTS_PER_PAGE = 10;

    /**
     * @Route("/list/{page}", name="form_builder_form_list", requirements={"page" = "\d+"})
     *
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(EntityManager $em, $page = 1)
    {
        $queryBuilder = $em->createQueryBuilder()
            ->select('f')
            ->from(Form::class, 'f');

        $paginator = new Pagerfanta(
            new DoctrineORMAdapter($queryBuilder)
        );
        $paginator->setMaxPerPage(self::RESULTS_PER_PAGE);
        $paginator->setCurrentPage($page);

        return $this->render('@FormBuilder/form_builder/form/list.html.twig', [
            'totalCount' => $paginator->getNbResults(),
            'forms'      => $paginator,
        ]);
    }

    /**
     * @Route("/new", name="form_builder_form_new")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $translator = $this->get('translator'); //TODO: get by autowire
        $formData   = new Form();
        $form       = $this->builderFormFactory->createEditForm($formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formData);
            $em->flush();

            return $this->redirectToRoute('form_builder_form_list');
        }

        return $this->render(
            '@FormBuilder/form_builder/form/edit.html.twig',
            [
                'form'                => $form->createView(),
                'title'               => $translator->trans('Create new form'),
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="form_builder_form_edit")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Form $formData)
    {
        $translator     = $this->get('translator'); //TODO: get by autowire
        $originalFields = new ArrayCollection();

        foreach ($formData->getFields() as $field) {
            $originalFields->add($field);
        }

        $form     = $this->builderFormFactory->createEditForm($formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($originalFields as $field) {
                /** @var Field $field */
                if (!$formData->getFields()->contains($field)) {
                    $field->setForm(null);
                    $em->persist($field);
                    $em->remove($field);
                }
            }

            $em->persist($formData);
            $em->flush();

            return $this->redirectToRoute('form_builder_form_list');
        }

        return $this->render(
            '@FormBuilder/form_builder/form/edit.html.twig',
            [
                'form'                => $form->createView(),
                'title'               => $translator->trans('Edit form'),
            ]
        );
    }

    /**
     * Test action to render & handle clientside form.
     *
     * @Route("/{id}", name="form_builder_form_show")
     */
    public function showAction(Form $formEntity, Request $request)
    {
        $form = $this->builderFormFactory->createCollectForm($formEntity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() &&
            $this->formSubmissionHelper->checkSubmissionAvailability($form, $formEntity)
        ) {
            /** @var User $user */
            $user = $this->getUser();
            $this->formSubmissionHelper->createAndLogSubmission(
                $form->getData(),
                $formEntity,
                $user ? $user->getAPIUser()->getUserId() : null
            );

            return $this->redirectToRoute('form_builder_submission_list');
        }

        return $this->render('@FormBuilder/form_builder/form/show.html.twig', [
            'formEntity' => $formEntity,
            'formView'   => $form->createView(),
        ]);
    }
}
