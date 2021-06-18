<?php
/**
 * @copyright Novactive
 * Date: 18/06/2021
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field\Compose;

trait Placeholder
{
    public function getPlaceholder(): ?string
    {
        return $this->getOption('placeholder');
    }

    public function setPlaceholder(?string $placeholder): void
    {
        $this->setOption('placeholder', $placeholder);
    }
}
