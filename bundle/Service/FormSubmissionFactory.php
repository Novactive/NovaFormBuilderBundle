<?php

namespace Novactive\Bundle\FormBuilderBundle\Service;

use Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission;

class FormSubmissionFactory
{
    /**
     * @param $data
     * @return FormSubmission
     */
    public function create($formEntity) : FormSubmission
    {
        $data = $this->prepareData($formEntity);

        $formSubmission = new FormSubmission();
        $formSubmission->setCreatedAt(new \DateTime());
        $formSubmission->setForm($formEntity);
        $formSubmission->setData($data);

        return $formSubmission;
    }

    /**
     * return [[name => 'Human readable name', value => field_value]]
     *
     * @param $data
     * @return array
     */
    private function prepareData($formEntity) : array
    {
//        foreach ($formEntity->getFields()) {
//
//        }
        return array_map(function($item, $key) use ($fields) {

            $name = $fields->filter(function($field) use ($key) {
                return $key == $field->getId();
            })->first()->getName();

            // datetime hack
            // TODO move data transformations to separate service
            if ($item instanceof \DateTimeInterface) {
                $item = $item->format('Y/m/d');
            }

            return [
                'name' => $name,
                'value' => $item
            ];

        }, $data, array_keys($data));
    }
}