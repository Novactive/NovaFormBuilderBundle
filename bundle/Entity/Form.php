<?php
/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="novaformbuilder_form")
 */
class Form
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="bigint")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string")
     *
     * @Assert\NotNull
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="max_submissions", type="bigint", nullable=true)
     *
     * @var int
     */
    private $maxSubmissions;

    /**
     * @ORM\OneToMany(targetEntity="Novactive\Bundle\FormBuilderBundle\Entity\Field", mappedBy="form",
     *                                                                                cascade={"persist", "remove"},
     *                                                                                  orphanRemoval=true)
     * @ORM\OrderBy({"weight" = "ASC"})
     *
     * @var Field[]
     */
    private $fields = [];

    /**
     * @ORM\OneToMany(targetEntity="Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission", mappedBy="form",
     *                                                                                         cascade={"persist",
     *                                                                            "remove"}, orphanRemoval=true)
     *
     * @var FormSubmission[]
     */
    private $submissions;

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->fields      = new ArrayCollection();
        $this->submissions = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMaxSubmissions(): ?int
    {
        if (null === $this->maxSubmissions) {
            return null;
        }

        return (int) $this->maxSubmissions;
    }

    /**
     * @return Form
     */
    public function setMaxSubmissions(?int $maxSubmissions): self
    {
        $this->maxSubmissions = $maxSubmissions;

        return $this;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @return Field[]|ArrayCollection
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

    public function addField(Field $field): self
    {
        if (!$this->fields->contains($field)) {
            $field->setForm($this);
            $this->fields->add($field);
        }

        return $this;
    }

    public function removeField(Field $field): self
    {
        $this->fields->removeElement($field);

        return $this;
    }

    /**
     * @return ArrayCollection|FormSubmission[]
     */
    public function getSubmissions()
    {
        return $this->submissions;
    }
}
