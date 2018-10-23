<?php
/**
 * NovaFormBuilder package.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Listener;

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormEvent;

class ResizeForm extends ResizeFormListener
{
    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = [];
        }

        if (!\is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            $data = [];
        }

        $event->setData($data);
        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        foreach ($data as $name => $value) {
            $form->add(
                $name,
                $this->type,
                array_replace(
                    [
                        'property_path' => '['.$name.']',
                    ],
                    $this->options
                )
            );
        }
    }

    public function preSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($data instanceof \Traversable && $data instanceof \ArrayAccess) {
            return;
        }

        if (!\is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            $data = [];
        }

        $reorderedData = [];

        usort(
            $data,
            function ($i, $j) {
                if (!isset($i['weight']) || !isset($j['weight'])) {
                    return 0;
                }

                if ($i['weight'] > $j['weight']) {
                    return 1;
                }

                if ($i['weight'] < $j['weight']) {
                    return -1;
                }

                return 0;
            }
        );

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
                $form->add(
                    $name,
                    $this->type,
                    array_replace(
                        [
                            'property_path' => '['.$name.']',
                        ],
                        $this->options
                    )
                );
            }
        }
    }
}
