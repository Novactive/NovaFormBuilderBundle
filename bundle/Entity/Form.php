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
use Novactive\Bundle\FormBuilderBundle\Entity\Field\ChoiceReceiver;
use Novactive\Bundle\FormBuilderBundle\Entity\Field\Email;
use Novactive\Bundle\FormBuilderBundle\Entity\Field\MailSubject;
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
     * @ORM\Column(name="id", type="integer")
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
     * @ORM\Column(name="subject_email", type="string", nullable=true)
     *
     * @var string
     */
    private $subjectEmail;

    /**
     * @ORM\Column(name="title_email", type="string", nullable=true)
     *
     * @var string
     */
    private $titleEmail;

    /**
     * @ORM\Column(name="send_data", type="boolean")
     *
     * @var bool
     */
    private $sendData;

    /**
     * Override sending data to user if done using another way.
     *
     * @var bool
     */
    private $overrideUserSendData = false;

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
     * @ORM\Column(name="date_start_submission", type="datetime", nullable=true)
     *
     * @var \DateTime|null
     */
    private $dateStartSubmission;

    /**
     * @ORM\Column(name="date_end_submission", type="datetime", nullable=true)
     *
     * @var \DateTime|null
     */
    private $dateEndSubmission;

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
        return '' !== $this->senderEmail ? $this->senderEmail : null;
    }

    public function setSenderEmail(?string $senderEmail): self
    {
        $this->senderEmail = $senderEmail;

        return $this;
    }

    public function getReceiverEmail(): ?string
    {
        $email = '';
        foreach ($this->getFields() as $field) {
            if ($field instanceof ChoiceReceiver && $field->getValue()) {
                $email = $field->getValue();
            }
        }

        return '' !== $email ? $email : $this->receiverEmail;
    }

    public function setReceiverEmail(?string $receiverEmail): self
    {
        $this->receiverEmail = $receiverEmail;

        return $this;
    }

    public function getSubjectEmail(): ?string
    {
        $subject = '';
        foreach ($this->getFields() as $field) {
            if ($field instanceof MailSubject && $field->getValue()) {
                $subject = $field->getValue();
            }
        }

        return $subject ? $subject : $this->subjectEmail;
    }

    public function setSubjectEmail(?string $subjectEmail): self
    {
        $this->subjectEmail = $subjectEmail;

        return $this;
    }

    public function getTitleEmail(): ?string
    {
        return $this->titleEmail;
    }

    public function setTitleEmail(?string $titleEmail): self
    {
        $this->titleEmail = $titleEmail;

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

    public function setOverrideUserSendData(bool $override): self
    {
        $this->overrideUserSendData = $override;

        return $this;
    }

    public function isUserSendData(): bool
    {
        foreach ($this->getFields() as $field) {
            if ($field instanceof Email && $field->isSendData() && $field->getValue()) {
                return true && !$this->overrideUserSendData;
            }
        }

        return false;
    }

    public function getUserSendEmails(): array
    {
        $emails = [];

        foreach ($this->getFields() as $field) {
            if ($field instanceof Email && $field->isSendData() && $field->getValue()) {
                $emails[] = $field->getValue();
            }
        }

        return $emails;
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

    public function getDateStartSubmission()
    {
        if (null === $this->dateStartSubmission) {
            return null;
        }

        return $this->dateStartSubmission;
    }

    public function setDateStartSubmission($dateStartSubmission)
    {
        $this->dateStartSubmission = $dateStartSubmission;

        return $this;
    }

    public function getDateEndSubmission()
    {
        if (null === $this->dateEndSubmission) {
            return null;
        }

        return $this->dateEndSubmission;
    }

    public function setDateEndSubmission($dateEndSubmission)
    {
        $this->dateEndSubmission = $dateEndSubmission;

        return $this;
    }
}
