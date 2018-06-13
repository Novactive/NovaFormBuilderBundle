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

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Field.
 *
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "textline" = "\Novactive\Bundle\FormBuilderBundle\Entity\Field\TextLine",
 *     "textarea" = "\Novactive\Bundle\FormBuilderBundle\Entity\Field\TextArea",
 *     "date" = "\Novactive\Bundle\FormBuilderBundle\Entity\Field\Date",
 *     "email" = "\Novactive\Bundle\FormBuilderBundle\Entity\Field\Email",
 *     "number" = "\Novactive\Bundle\FormBuilderBundle\Entity\Field\Number",
 *     "time" = "\Novactive\Bundle\FormBuilderBundle\Entity\Field\Time"
 * })
 * @ORM\Table(name="novaformbuilder_field")
 *
 * TODO: @see https://medium.com/@jasperkuperus/defining-discriminator-maps-at-child-level-in-doctrine-2-1cd2ded95ffb
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity
 */
abstract class Field
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
     * @ORM\Column(type="string", name="name")
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $required = true;

    /**
     * Used to order form fields.
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $weight = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="fields")
     *
     * @var Form
     */
    protected $form;

    /**
     * @ORM\Column(type="json", name="options")
     *
     * @var array
     */
    protected $options = [];

    /**
     * Field constructor.
     */
    public function __construct()
    {
        $this->name = '';
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     */
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;

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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     */
    public function setForm(Form $form): void
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getOption($name)
    {
        return $this->options[$name] ?? false;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setOption($name, $value): void
    {
        $this->options[$name] = $value;
    }

    public function __toString()
    {
        return $this->getType();
    }
}
