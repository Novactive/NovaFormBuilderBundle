<?php

namespace Novactive\Bundle\FormBuilderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionHeaderType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined([
            'disabled' => true,
            'required' => false,
            'mapped'   => false,
        ]);
    }

    public function getName()
    {
        return 'section_header';
    }
}