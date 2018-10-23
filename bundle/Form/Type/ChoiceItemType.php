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

namespace Novactive\Bundle\FormBuilderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ChoiceItemType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'novaformbuilder_choice_item';
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'value',
            TextType::class,
            [
                'attr' => [
                    'placeholder' => 'novaformbuilder_field.choice.choices.value',
                ],
            ]
        )->add(
            'weight',
            TextType::class,
            [
                'attr' => [
                    'placeholder' => 'novaformbuilder_field.choice.choices.weight',
                ],
            ]
        );
    }
}
