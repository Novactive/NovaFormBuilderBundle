<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Traits\IdentityTrait;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;

/**
 * Class FormSubmission.
 *
 * @ORM\Entity()
 * @ORM\Table(name="novaformbuilder_form_submision")
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity
 */
class FormSubmission
{
    use IdentityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="submissions")
     *
     * @var Novactive\Bundle\FormBuilderBundle\Entity\Form
     */
    private $form;

    /**
     * @ORM\Column(type="json")
     *
     * @var array
     */
    private $data;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @return Novactive\Bundle\FormBuilderBundle\Entity\Form
     */
    public function getForm():Form
    {
        return $this->form;
    }

    /**
     * @param Novactive\Bundle\FormBuilderBundle\Entity\Form $form
     */
    public function setForm(Form $form): void
    {
        $this->form = $form;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
