<?php

namespace Novactive\Bundle\FormBuilderBundle\Service;

use Doctrine\Common\Collections\Collection;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission;

class FormSubmissionFactory
{
    /**
     * @param array $data
     * @param Form  $formEntity
     * @param null  $userId
     *
     * @return FormSubmission
     */
    public function create(array $data, Form $formEntity, $userId = null): FormSubmission
    {
        $data = $this->prepareData($data, $formEntity->getFields());

        $formSubmission = new FormSubmission();
        $formSubmission->setCreatedAt(new \DateTime());
        $formSubmission->setForm($formEntity);
        $formSubmission->setData($data);
        $formSubmission->setUserId($userId);

        return $formSubmission;
    }

    /**
     * Get aray like [internal_field_name_N => field_value]
     * and return [[name => 'Human readable name', value => field_value]].
     *
     * @param $data
     * @param Collection $fields
     *
     * @return array
     */
    private function prepareData($data, Collection $fields): array
    {
        return array_map(function ($item, $key) use ($fields) {
            $name = $fields->filter(function ($field) use ($key) {
                /* @var Field $field */
                return $key == $field->getId();
            })->first()->getName();

            // datetime hack
            // TODO move data transformations to separate service
            if ($item instanceof \DateTimeInterface) {
                $item = $item->format('Y/m/d');
            }

            return [
                'name'  => $name,
                'value' => $item,
            ];
        }, $data, array_keys($data));
    }
}
