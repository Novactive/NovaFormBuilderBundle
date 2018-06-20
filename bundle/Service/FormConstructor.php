<?php

namespace Novactive\Bundle\FormBuilderBundle\Service;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;

class FormConstructor
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function build(Form $formView)
    {
        $formBuilder = $this->formFactory->createBuilder(FormType::class);
        $formFields = $formView->getFields(); // already sorted in OrderBy({"weight" = "ASC"})

        foreach ($formFields as $field) {
            $options = [
                //'mapped'   => false,
                'required' => $field->isRequired(),
            ];

            $fieldOptions = $field->getOptions();

            if (!empty($fieldOptions)) {
                // TODO implement validator
            }

            $formBuilder->add($field->getName(), $field->getFormTypeClass(), $options);
        }

        return $formBuilder->getForm();
    }

}