<?php
/**
 * @copyright Novactive
 * Date: 01/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Form\Type;

use Novactive\Bundle\FormBuilderBundle\Field\FieldTypeFormMapperDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldEditType extends AbstractType
{
    /**
     * @var FieldTypeFormMapperDispatcherInterface
     */
    private $fieldTypeMapperDispatcher;

    /**
     * FieldEditType constructor.
     *
     * @param FieldTypeFormMapperDispatcherInterface $fieldTypeMapperDispatcher
     */
    public function __construct(FieldTypeFormMapperDispatcherInterface $fieldTypeMapperDispatcher)
    {
        $this->fieldTypeMapperDispatcher = $fieldTypeMapperDispatcher;
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'novaformbuilder_field_edit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'data_class'         => 'Novactive\Bundle\FormBuilderBundle\Entity\Field',
                    'translation_domain' => 'novaformbuilder_field',
                ]
            );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'novaformbuilder_field.name',
            ])
            ->add('required', null, [
                'label' => 'novaformbuilder_field.required',
            ])
            ->add('weight', null, [
                'label' => 'novaformbuilder_field.weight',
            ])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var \EzSystems\RepositoryForms\Data\FieldDefinitionData $data */
                $data = $event->getData();
                $form = $event->getForm();

                if ($data) {
                    // Let fieldType mappers do their jobs to complete the form.
                    $this->fieldTypeMapperDispatcher->mapFieldEditForm($form, $data);
                }
            }
        );
    }
}
