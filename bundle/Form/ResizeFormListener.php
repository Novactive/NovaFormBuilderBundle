<?php
/**
 * @copyright Novactive
 * Date: 06/07/18
 */

namespace Novactive\Bundle\FormBuilderBundle\Form;

use Symfony\Component\Form\FormEvent;

class ResizeFormListener extends \Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener
{
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = [];
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            $data = [];
        }

        $event->setData($data);
        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        foreach ($data as $name => $value) {
            $form->add($name, $this->type, array_replace([
                'property_path' => '['.$name.']',
            ], $this->options));
        }
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($data instanceof \Traversable && $data instanceof \ArrayAccess) {
            @trigger_error('Support for objects implementing both \Traversable and \ArrayAccess is deprecated since Symfony 3.1 and will be removed in 4.0. Use an array instead.', E_USER_DEPRECATED);
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            $data = [];
        }

        $reorderedData = [];

        usort($data, function ($a, $b) {
            if (!isset($a['weight']) || !isset($b['weight'])) {
                return 0;
            }

            if ($a['weight'] > $b['weight']) {
                return 1;
            }

            if ($a['weight'] < $b['weight']) {
                return -1;
            }

            return 0;
        });

        $counter = 1;
        foreach ($data as $key => $value) {
            $reorderedData[$counter] = $value;
            ++$counter;
        }

        $data = $reorderedData;
        $event->setData($data);

        // Remove all rows from form
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        // Add all rows
        if ($this->allowAdd) {
            foreach ($data as $name => $value) {
                $form->add($name, $this->type, array_replace([
                    'property_path' => '['.$name.']',
                ], $this->options));
            }
        }
    }
}
