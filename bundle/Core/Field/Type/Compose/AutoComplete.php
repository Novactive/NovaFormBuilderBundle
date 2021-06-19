<?php
/**
 * @copyright Novactive
 * Date: 18/06/2021
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Core\Field\Type\Compose;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;

trait AutoComplete
{
    public function addAutoCompleteField(FormInterface $fieldForm)
    {
        $choices = [
            'off'                                         => 'autocomplete.off',
            'on'                                          => 'autocomplete.on',
            'name'                                        => 'autocomplete.name',
            'honorific-prefix'                            => 'autocomplete.honorific-prefix',
            'given-name'                                  => 'autocomplete.given-name',
            'additional-name'                             => 'autocomplete.additional-name',
            'family-name'                                 => 'autocomplete.family-name',
            'honorific-suffix'                            => 'autocomplete.honorific-suffix',
            'nickname'                                    => 'autocomplete.nickname',
            'email'                                       => 'autocomplete.email',
            'username'                                    => 'autocomplete.username',
            'organization-title'                          => 'autocomplete.organization-title',
            'organization'                                => 'autocomplete.organization',
            'street-address'                              => 'autocomplete.street-address',
            'country'                                     => 'autocomplete.country',
            'country-name'                                => 'autocomplete.country-name',
            'postal-code'                                 => 'autocomplete.postal-code',
            'language'                                    => 'autocomplete.language',
            'bday'                                        => 'autocomplete.bday',
            'bday-day'                                    => 'autocomplete.bday-day',
            'bday-month'                                  => 'autocomplete.bday-month',
            'bday-year'                                   => 'autocomplete.bday-year',
            'sex'                                         => 'autocomplete.sex',
            'tel'                                         => 'autocomplete.tel',
            'url'                                         => 'autocomplete.url',
        ];

        $fieldForm->add(
            'autoComplete',
            ChoiceType::class,
            [
                'label'    => 'field.autoComplete',
                'choices'  => array_flip($choices),
                'required' => false,
            ]
        );
    }
}
