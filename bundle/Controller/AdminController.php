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
use Novactive\Bundle\FormBuilderBundle\Form\Type\FormEditType;
use Novactive\Bundle\FormBuilderBundle\Form\Type\FieldEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class AdminController.
 *
 * @Route("/form-builder/admin")
 *
 * @package Novactive\Bundle\FormBuilderBundle\Controller
 */
class AdminController extends Controller
{
    const FIELDS_CLASS_MAP = [
        "textline" => "TextLine",
        "textarea" => "TextArea",
        "date" => "Date",
        "email" => "Email",
        "number" => "Number",
        "time" => "Time"
    ];

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

        $form = $this->createForm(FormEditType::class, $formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formData);
            $em->flush();
        }

        return $this->render('@FormBuilder/form_builder/form/new.html.twig', [
            'form'              => $form->createView(),
            'available_fields' => self::FIELDS_CLASS_MAP
        ]);
    }

    /**
     * @Route("/get-field-form")
     *
     * @Method("GET")
     *
     * @param Request $request
     */
    public function getFieldForm(Request $request)
    {
        $fieldType = $request->query->get('field_type');
        $fieldName = $this->getFieldClassName($fieldType);
        $className = '\\Novactive\\Bundle\\FormBuilderBundle\\Entity\\Field\\' . $fieldName;

        if (class_exists($className)) {
            $field = new $className();
        } else {
            throw new \Exception('Wrong field type');
        }

        $formData = new Form();
        $formData->addField($field);
        $form = $this->createForm(FormEditType::class, $formData, ['field_class' => $field]);

        $prototype = $this->renderView(
            '@FormBuilder/form_builder/form/field_prototype.json.twig',
            [
                'form' => $form->createView()->children['fields']
            ]
        );

        return $this->json([
            'data' => [
                'prototype' => $prototype,
                'field_name' => $fieldName
            ]
        ]);
    }

    private function getFieldClassName($fieldType)
    {
        return self::FIELDS_CLASS_MAP[strtolower($fieldType)] ?? null;
    }
}
