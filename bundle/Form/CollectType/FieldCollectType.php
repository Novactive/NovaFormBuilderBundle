<?php
/**
 * @copyright Novactive
 * Date: 01/06/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Form\CollectType;

use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldFormMapperInterface;
use Novactive\Bundle\FormBuilderBundle\Service\FieldTypeRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldCollectType extends AbstractType
{
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
                    'field_types'        => [],
                ]
            );
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Field $data */
                $data = $event->getData();
                $form = $event->getForm();

                if ($data) {
                    /** @var FieldFormMapperInterface[] $fieldTypes */
                    $fieldTypes = $form->getConfig()->getOption('field_types', []);

                    foreach ($fieldTypes as $fieldType) {

                        if (!$fieldType instanceof FieldFormMapperInterface) {
                            throw new \InvalidArgumentException(
                                'FieldEditType field_types option require a FieldFormMapperInterface value'
                            );
                        }
                        if ($fieldType->accept($data)) {
                            $fieldType->mapFieldCollectForm($form, $data);
                        }
                    }
                }
            }
        );
    }
}
