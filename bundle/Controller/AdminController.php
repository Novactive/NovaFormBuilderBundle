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

namespace Novactive\Bundle\FormBuilderBundle\Controller;

use Doctrine\ORM\EntityManager;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Form\FormEditFormFactory;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AdminController.
 *
 * @Route("/form-builder/admin")
 *
 * @package Novactive\Bundle\FormBuilderBundle\Controller
 */
class AdminController extends Controller
{
    /** @var FormEditFormFactory */
    protected $formEditFormFactory;

    /**
     * AdminController constructor.
     *
     * @param FormEditFormFactory $formEditFormFactory
     */
    public function __construct(FormEditFormFactory $formEditFormFactory)
    {
        $this->formEditFormFactory = $formEditFormFactory;
    }

    const RESULTS_PER_PAGE = 10;

    /**
     * @Route("/list/{page}", name="form_builder_form_list", requirements={"page" = "\d+"})
     *
     * @param EntityManager $em
     * @param int           $page
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
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $translator = $this->get('translator'); //TODO: get by autowire
        $formData = new Form();
        $form     = $this->formEditFormFactory->createForm($formData);
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
                'title' => $translator->trans('Create new form')
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="form_builder_form_edit")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Form $formData)
    {
        $translator = $this->get('translator'); //TODO: get by autowire
        $originalFields = new ArrayCollection();

        foreach ($formData->getFields() as $field) {
            $originalFields->add($field);
        }

        $form     = $this->formEditFormFactory->createForm($formData);
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
                'title' => $translator->trans('Edit form')
            ]
        );
    }

    /**
     * @Route("/{id}", name="form_builder_form_show")
     *
     * @param Form $formView
     */
    public function showAction(Form $formView)
    {
//        $formBuilder = $this->createFormBuilder();
//
//        // TODO: move to separate service
//        foreach ($formView->getFields() as $field) {
//            $options = [
//                'mapped'   => false,
//                'required' => $field->isRequired(),
//            ];
//
//            $fieldOptions = $field->getOptions();
//
//            if (!empty($fieldOptions)) {
//                // TODO implement validator
//            }
//
//            $formBuilder->add($field->getName(), $field->getTypeClass(), $options);
//        }
//
//        $form = $formBuilder->getForm();
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $data = $form->getData();
//
//            // create new FormSubmission based on data
//
//            /*$em = $this->getDoctrine()->getManager();
//            $em->persist($formData);
//            $em->flush();*/
//
//            return $this->redirectToRoute('form_builder_form_list');
//        }
//
//        return $this->render('@FormBuilder/form_builder/form/show.html.twig', [
//            'formView' => $form->createView(),
//        ]);
    }
}
