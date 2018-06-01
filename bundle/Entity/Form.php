<?php
/**
 * NovaFormBuilderBundle.
 *
 * @package   NovaFormBuilderBundle
 *
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE
 */

namespace Novactive\Bundle\FormBuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Form.
 *
 * @ORM\Entity()
 * @ORM\Table(name="novaformbuilder_form")
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity
 */
class Form
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="id')
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Field", mappedBy="form")
     *
     * @var Field[]
     */
    public $fields = [];

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Field[]
     */
    public function getFields(): ArrayCollection
    {
        return $this->fields;
    }

    /**
     * @param Field[] $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $this->fields->add($field);
    }

    /**
     * @param Field $field
     */
    public function removeField(Field $field)
    {
        $this->fields->removeElement($field);
    }
}
