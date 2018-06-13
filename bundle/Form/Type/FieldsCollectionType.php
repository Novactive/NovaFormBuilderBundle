<?php
/**
 * @copyright Novactive
 * Date: 12/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Form\Type;

use Novactive\Bundle\FormBuilderBundle\EventListener\ResizeCollectionFormListener;
use Novactive\Bundle\FormBuilderBundle\Field\FieldTypeRegistry;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldsCollectionType extends CollectionType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['allow_add'] && $options['prototype']) {
            $prototypeOptions = array_replace(array(
                'required' => $options['required'],
                'label' => $options['prototype_name'].'label__',
            ), $options['entry_options']);

            if (null !== $options['prototype_data']) {
                $prototypeOptions['data'] = $options['prototype_data'];
            }

            $prototype = $builder->create($options['prototype_name'], $options['entry_type'], $prototypeOptions);
            $builder->setAttribute('prototype', $prototype->getForm());
        }

        // Replace default listener by ours
        $resizeListener = new ResizeCollectionFormListener(
            $options['entry_type'],
            $options['entry_options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['delete_empty']
        );

        $builder->addEventSubscriber($resizeListener);

        $fieldTypesPrototype = [];

        $prototypeOptions = array_replace(
            [
                'required' => $options['required'],
                'label' => $options['prototype_name'] . 'label__',
            ],
            $options['entry_options']
        );

        foreach ($options['field_types'] as $fieldType) {
            $prototypeOptions['data'] = $fieldType->getEntity();
            $prototype = $builder->create(
                $options['prototype_name'],
                $options['entry_type'],
                $prototypeOptions
            );
            $fieldTypesPrototype[$fieldType->getIdentifier()] = $prototype->getForm();
        }

        $builder->setAttribute('field_types_prototype', $fieldTypesPrototype);
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if ($form->getConfig()->hasAttribute('field_types_prototype')) {
            $prototypes = $form->getConfig()->getAttribute('field_types_prototype');
            $fieldTypesPrototype = [];

            foreach ($prototypes as $fieldTypeIdentifier => $prototype) {
                $fieldTypesPrototype[$fieldTypeIdentifier] = $prototype->setParent($form)->createView($view);
            }

            $view->vars['field_types_prototype'] = $fieldTypesPrototype;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('field_types', []);
    }

    public function getBlockPrefix()
    {
        return 'novaformbuilder_fields_collection';
    }
}
