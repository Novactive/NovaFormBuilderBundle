<?php
/**
 * @copyright Novactive
 * Date: 01/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Form\Type;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldTypeFormMapperDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldCollectType extends AbstractType
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
        return 'novaformbuilder_field_collect';
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
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'novaformbuilder_field.name',
            ]
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Field $data */
                $data = $event->getData();
                $form = $event->getForm();

                // Let fieldType mappers do their jobs to complete the form.
                $this->fieldTypeMapperDispatcher->mapFieldCollectForm($form, $data);
            }
        );
    }
}
