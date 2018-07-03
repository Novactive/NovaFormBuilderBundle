<?php
/**
 * Created by PhpStorm.
 * User: domain
 * Date: 03.07.18
 * Time: 15:43
 */

namespace Novactive\Bundle\FormBuilderBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ChoiceItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', TextType::class);
        $builder->add('weight', TextType::class);
    }
}