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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Util\StringUtil;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\Table(name="novaformbuilder_field")
 */
abstract class Field
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
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="required", type="boolean")
     *
     * @var bool
     */
    private $required;

    /**
     * Used to order form fields.
     *
     * @ORM\Column(name="weight", type="integer")
     *
     * @var int
     */
    private $weight;

    /**
     * @ORM\Column(name="css_class", type="string", nullable=true)
     *
     * @var string
     */
    private $cssClass;

    /**
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="fields")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Form
     */
    private $form;

    /**
     * @ORM\Column(name="options", type="json")
     *
     * @var array
     */
    private $options = [];

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Field constructor.
     */
    public function __construct(array $properties = [])
    {
        $this->required = true;
        $this->weight   = 0;
        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }
    }

    public function getValue()
    {
        return $this->value ?? '';
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return StringUtil::fqcnToBlockPrefix(\get_class($this));
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    public function setForm(?Form $form): self
    {
        $this->form = $form;

        return $this;
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

    public function getCssClass(): ?string
    {
        return $this->cssClass;
    }

    public function setCssClass(?string $cssClass): self
    {
        $this->cssClass = $cssClass;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getOption(string $name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }

    public function setOption(string $name, $value): self
    {
        $this->options[$name] = $value;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getType();
    }
}
