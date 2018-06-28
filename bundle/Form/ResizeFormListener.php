<?php
/**
 * Created by PhpStorm.
 * User: domain
 * Date: 27.06.18
 * Time: 14:49
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
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            $data = [];
        }

        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        // Then add all rows again in the correct order
        foreach ($data as $name => $value) {
            $form->add($name, $this->type, array_replace(array(
                'property_path' => '['.$name.']',
            ), $this->options));
        }
    }
}