<?php
/**
 * NovaFormBuilder package.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Core;

use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Form\CollectType\FormCollectType;
use Novactive\Bundle\FormBuilderBundle\Form\EditType\FormEditType;
use Symfony\Component\Form\FormFactory as BaseFormFactory;
use Symfony\Component\Form\FormInterface;

class FormFactory
{
    /**
     * @var BaseFormFactory
     */
    protected $formFactory;

    /**
     * @var FieldTypeRegistry
     */
    protected $fieldTypeRegistry;

    public function __construct(BaseFormFactory $formFactory, FieldTypeRegistry $fieldTypeRegistry)
    {
        $this->formFactory       = $formFactory;
        $this->fieldTypeRegistry = $fieldTypeRegistry;
    }

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
