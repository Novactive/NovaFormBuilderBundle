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

namespace Novactive\Bundle\eZFormBuilderBundle\eZ\Publish\FieldType\CustomForm;

use eZ\Publish\API\Repository\FieldTypeService;
use EzSystems\RepositoryForms\Data\Content\FieldData;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Novactive\Bundle\eZFormBuilderBundle\Form\CustomFormValueTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;

class FormMapper implements FieldValueFormMapperInterface
{
    /** @var FieldTypeService */
    private $fieldTypeService;

    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
    }

    /**
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data): void
    {
        $fieldDefinition = $data->fieldDefinition;
        $formConfig      = $fieldForm->getConfig();
        $names           = $fieldDefinition->getNames();
        $label           = $fieldDefinition->getName($formConfig->getOption('mainLanguageCode')) ?: reset($names);
        $fieldType       = $this->fieldTypeService->getFieldType($fieldDefinition->fieldTypeIdentifier);
        $fieldForm
            ->add(
                $formConfig->getFormFactory()
                           ->createBuilder()
                           ->create(
                               'value',
                               TextType::class,
                               [
                                   'required' => false,
                                   'label'    => $label,
                               ]
                           )
                    // Deactivate auto-initialize as we're not on the root form.
                           ->setAutoInitialize(false)
                           ->addModelTransformer(new CustomFormValueTransformer($fieldType))
                           ->getForm()
            );
    }
}
