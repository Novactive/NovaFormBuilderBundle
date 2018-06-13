<?php

namespace Novactive\Bundle\FormBuilderBundle\EventListener;

use Novactive\Bundle\FormBuilderBundle\Entity\Field\Date;
use Novactive\Bundle\FormBuilderBundle\Entity\Field\Email;
use Novactive\Bundle\FormBuilderBundle\Entity\Field\Number;
use Novactive\Bundle\FormBuilderBundle\Entity\Field\TextArea;
use Novactive\Bundle\FormBuilderBundle\Entity\Field\TextLine;
use Novactive\Bundle\FormBuilderBundle\Entity\Field\Time;
use Novactive\Bundle\FormBuilderBundle\Field\FieldTypeRegistry;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class ResizeCollectionFormListener
 *
 * Almost copy of ResizeFormCollection
 *
 *
 * @package Novactive\Bundle\FormBuilderBundle\EventListener
 */
class ResizeCollectionFormListener extends ResizeFormListener implements EventSubscriberInterface
{
    const FIELD_TYPE_PROP_NAME = '_type';

    // TODO: refactor
    const FIELD_ENTITY_TYPES = [
        'date' => Date::class,
        'email' => Email::class,
        'text_line' => TextLine::class,
        'text_area' => TextArea::class,
        'number' => Number::class,
        'time' => Time::class
    ];

    /**
     * Altrered listener
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($data instanceof \Traversable && $data instanceof \ArrayAccess) {
            @trigger_error('Support for objects implementing both \Traversable and \ArrayAccess is deprecated since Symfony 3.1 and will be removed in 4.0. Use an array instead.', E_USER_DEPRECATED);
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            $data = array();
        }

        // Remove all empty rows
        if ($this->allowDelete) {
            foreach ($form as $name => $child) {
                if (!isset($data[$name])) {
                    $form->remove($name);
                }
            }
        }

        // Add all additional rows
        if ($this->allowAdd) {
            foreach ($data as $name => $value) {
                if (!$form->has($name)) {

                    // Set options for new rows
                    $dataClass = self::FIELD_ENTITY_TYPES[$value[self::FIELD_TYPE_PROP_NAME]];
                    $form->add($name, $this->type, array_replace([
                        'property_path' => '['.$name.']',
                        'data_class' => $dataClass,
                        'allow_extra_fields' => true,
                        'by_reference' => false
                    ], $this->options));
                }
            }
        }
    }
}
