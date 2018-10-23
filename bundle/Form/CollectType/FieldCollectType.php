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

namespace Novactive\Bundle\FormBuilderBundle\Form\CollectType;

use InvalidArgumentException;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Field\FieldTypeMapperInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldCollectType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'novaformbuilder_field_collect';
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
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Field $field */
                $field = $event->getData();
                $form  = $event->getForm();

                if ($field) {
                    $fieldTypes = $form->getConfig()->getOption('field_types', []);
                    foreach ($fieldTypes as $fieldType) {
                        if (!$fieldType instanceof FieldTypeMapperInterface) {
                            throw new InvalidArgumentException(
                                'TODO BETTER EXCEPTION NAME 1'
                            );
                        }
                        if ($fieldType->supports($field)) {
                            $fieldType->mapFieldCollectForm($form, $field);
                        }
                    }
                }
            }
        );
    }
}
