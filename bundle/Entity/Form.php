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
use Novactive\Bundle\FormBuilderBundle\Entity\Field\Email;
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
     * @ORM\Column(name="sender_email", type="string", nullable=true)
     *
     * @Assert\Email()
     *
     * @var string
     */
    private $senderEmail;

    /**
     * @ORM\Column(name="receiver_email", type="string", nullable=true)
     *
     * @Assert\Email()
     *
     * @var string
     */
    private $receiverEmail;

    /**
     * @ORM\Column(name="send_data", type="boolean")
     *
     * @var bool
     */
    private $sendData;

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

    public function setMaxSubmissions(?int $maxSubmissions): self
    {
        $this->maxSubmissions = $maxSubmissions;

        return $this;
    }

    public function getSenderEmail(): ?string
    {
        return $this->senderEmail;
    }

    public function setSenderEmail(?string $senderEmail): self
    {
        $this->senderEmail = $senderEmail;

        return $this;
    }

    public function getReceiverEmail(): ?string
    {
        return $this->receiverEmail;
    }

    public function setReceiverEmail(?string $receiverEmail): self
    {
        $this->receiverEmail = $receiverEmail;

        return $this;
    }

    public function isSendData(): bool
    {
        return $this->sendData ?? false;
    }

    public function setSendData(bool $sendData): self
    {
        $this->sendData = $sendData;

        return $this;
    }

    public function isUserSendData(): bool
    {
        foreach ($this->getFields() as $field) {
            if ($field instanceof Email) {
                return $field->isSendData();
            }
        }

        return false;
    }

    public function getUserSendEmail(): ?string
    {
        foreach ($this->getFields() as $field) {
            if ($field instanceof Email) {
                return $field->getValue();
            }
        }

        return null;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getFields()
    {
        return $this->fields;
    }

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

    public function getSubmissions()
    {
        return $this->submissions;
    }

    public function setSubmissions(array $submissions): void
    {
        $this->submissions = $submissions;
    }

    public function addSubmission(FormSubmission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $submission->setForm($this);
            $this->submissions->add($submission);
        }

        return $this;
    }

    public function removeSubmission(FormSubmission $submission): self
    {
        $this->submissions->removeElement($submission);

        return $this;
    }
}
