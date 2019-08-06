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

use MCC\Bundle\CaptchaBundle\Form\Type\MCCCaptchaType;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormCollectType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'novaformbuilder_form_collect';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'data_class'         => Form::class,
                    'translation_domain' => 'novaformbuilder',
                    'field_types'        => [],
                ]
            );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'fields',
            CollectionType::class,
            [
                'entry_type'    => FieldCollectType::class,
                'label'         => 'fields',
                'entry_options' => [
                    'field_types' => $options['field_types'],
                ],
            ]
        );
    }
}
