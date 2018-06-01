<?php
/**
 * NovaFormBuilderBundle.
 *
 * @package   NovaFormBuilderBundle
 *
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE
 */

namespace Novactive\Bundle\FormBuilderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormCollectType extends AbstractType
{
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'novaformbuilder_form_collect';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'data_class'         => 'Novactive\Bundle\FormBuilderBundle\Entity\Form',
                    'translation_domain' => 'novaformbuilder_form',
                ]
            );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'fields',
            CollectionType::class,
            [
                'entry_type'    => FieldCollectType::class,
                'label'         => 'novaformbuilder_form.field',
            ]
        );
    }
}
