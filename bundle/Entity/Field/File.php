<?php

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class File
 *
 * @ORM\Entity()
 *
 * @package Novactive\Bundle\FormBuilderBundle\Entity\Field
 */
class File extends Field
{
    /**
     * @return string
     */
    public function getFileType(): string
    {
        return $this->getOption('fileType');
    }

    /**
     * @param string $fileType
     */
    public function setFileType($fileType)
    {
        $this->setOption('fileType', $fileType);
    }

    /**
     * @return float
     */
    public function getMaxFileSizeMb(): float
    {
        return $this->getOption('maxFileSizeMb') ?? 0;
    }

    /**
     * @param float $maxFileSizeMb
     */
    public function setMaxFileSizeMb($maxFileSizeMb)
    {
        $this->setOption('maxFileSizeMb', $maxFileSizeMb);
    }

    /**
     * @return string
     */
    public function getFormTypeClass()
    {
        return FileType::class;
    }
}