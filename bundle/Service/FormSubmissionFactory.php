<?php

namespace Novactive\Bundle\FormBuilderBundle\Service;

use Novactive\Bundle\FormBuilderBundle\Entity\Form;
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
    private function prepareData(Form $formEntity) : array
    {
        $data = [];
        foreach ($formEntity->getFields() as $field) {
            $data[] = [
                'name' => $field->getName(),
                'value' => $field->getValue()
            ];
        }

        return $data;
    }
}