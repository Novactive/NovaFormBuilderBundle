<?php

namespace Novactive\Bundle\bundlezzz\Service;

use Novactive\Bundle\bundlezzz\Entity\Form;
use Novactive\Bundle\bundlezzz\Entity\FormSubmission;

class FormSubmissionFactory
{
    /**
     * @param $data
     */
    public function create($formEntity): FormSubmission
    {
        $data = $this->prepareData($formEntity);

        $formSubmission = new FormSubmission();
        $formSubmission->setCreatedAt(new \DateTime());
        $formSubmission->setForm($formEntity);
        $formSubmission->setData($data);

        return $formSubmission;
    }

    /**
     * return [[name => 'Human readable name', value => field_value]].
     *
     * @param $data
     */
    private function prepareData(Form $formEntity): array
    {
        $data = [];
        foreach ($formEntity->getFields() as $field) {
            $data[] = [
                'name'  => $field->getName(),
                'value' => $field->getValue(),
            ];
        }

        return $data;
    }
}
