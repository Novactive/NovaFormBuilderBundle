<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Service;

use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Field\FieldTypeRegistry;
use Novactive\Bundle\FormBuilderBundle\Form\CollectType\FormCollectType;
use Novactive\Bundle\FormBuilderBundle\Form\EditType\FormEditType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class BuilderFormFactory
{
    /** @var FormFactory */
    protected $formFactory;

    /** @var FieldTypeRegistry */
    protected $fieldTypeRegistry;

    /**
     * FormEditFactory constructor.
     *
     * @param FormFactory       $formFactory
     * @param FieldTypeRegistry $fieldTypeRegistry
     */
    public function __construct(FormFactory $formFactory, FieldTypeRegistry $fieldTypeRegistry)
    {
        $this->formFactory       = $formFactory;
        $this->fieldTypeRegistry = $fieldTypeRegistry;
    }

    /**
     * @param Form $formData
     *
     * @return FormInterface
     */
    public function createEditForm(Form $formData): FormInterface
    {
        $form = $this->formFactory->create(
            FormEditType::class,
            $formData,
            ['field_types' => $this->fieldTypeRegistry->getFieldTypes()]
        );

        return $form;
    }

    public function createCollectForm(Form $formData): FormInterface
    {
        $form = $this->formFactory->create(
            FormCollectType::class,
            $formData,
            ['field_types' => $this->fieldTypeRegistry->getFieldTypes()]
        );

        return $form;
    }
}
