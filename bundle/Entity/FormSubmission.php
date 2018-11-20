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

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="novaformbuilder_form_submission")
 */
class FormSubmission
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
     * @ORM\ManyToOne(targetEntity="Novactive\Bundle\FormBuilderBundle\Entity\Form", inversedBy="submissions")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Form
     */
    private $form;

    /**
     * @ORM\Column(name="data", type="json", nullable=false)
     *
     * @var array
     */
    private $data;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @var DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(name="user_id", type="bigint", nullable=true)
     *
     * @var int|null
     */
    private $userId;

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    public function setForm(Form $form): self
    {
        $this->form = $form;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
