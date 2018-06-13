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

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Form\FormEditFormFactory;
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

    /**
     * @Route("/new")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $formData = new Form();
        $formData->addField(new Field\TextLine());
        $formData->addField(new Field\TextLine());
        $form = $this->formEditFormFactory->createForm($formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formData);
            $em->flush();
        }

        return $this->render(
            '@FormBuilder/form_builder/form/new.html.twig',
            [
                'form'                => $form->createView(),
            ]
        );
    }
}
