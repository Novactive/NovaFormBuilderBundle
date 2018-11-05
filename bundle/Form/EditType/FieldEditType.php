<?php
/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Form\EditType;

use InvalidArgumentException;
use Novactive\Bundle\FormBuilderBundle\Core\Field\FieldTypeMapperInterface;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldEditType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'novaformbuilder_field_edit';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'data_class'         => Field::class,
                    'translation_domain' => 'novaformbuilder',
                    'field_types'        => [],
                ]
            );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'field.name'])
            ->add('required', CheckboxType::class, ['label' => 'field.required', 'required' => false])
            ->add('weight', NumberType::class, ['label' => 'field.weight'])
            ->add('type', HiddenType::class);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Field $field */
                $field = $event->getData();
                $form  = $event->getForm();

                if ($field) {
                    $fieldTypes = $form->getConfig()->getOption('field_types');
                    foreach ($fieldTypes as $fieldType) {
                        if (!$fieldType instanceof FieldTypeMapperInterface) {
                            throw new InvalidArgumentException(
                                'A FieldType not implementing FieldTypeMapperInterface has been passed: '.
                                \get_class($fieldType)
                            );
                        }
                        if ($fieldType->supportsEntity($field)) {
                            $fieldType->mapFieldEditForm($form, $field);
                        }
                    }
                }
            }
        );
    }
}
