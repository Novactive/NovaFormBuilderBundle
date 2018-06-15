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
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="integer", name="id")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotNull()
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    protected $maxSubmissions;

    /**
     * @ORM\OneToMany(targetEntity="Field", mappedBy="form", cascade={"persist", "remove"})
     * @ORM\OrderBy({"weight" = "ASC"})
     *
     * @var Field[]
     */
    public $fields = [];

    /**
     * @ORM\OneToMany(targetEntity="FormSubmission", mappedBy="form", cascade={"persist", "remove"})
     *
     * @var FormSubmission[]
     */
    protected $submissions;

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxSubmissions()
    {
        return $this->maxSubmissions;
    }

    /**
     * @param int $maxSubmissions
     */
    public function setMaxSubmissions(int $maxSubmissions): self
    {
        $this->maxSubmissions = $maxSubmissions;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Field[]
     */
    public function getFields()
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
        if (!$this->fields->contains($field)) {
            $field->setForm($this);
            $this->fields->add($field);
        }
    }

    /**
     * @param Field $field
     */
    public function removeField(Field $field)
    {
        $this->fields->removeElement($field);
    }
}
