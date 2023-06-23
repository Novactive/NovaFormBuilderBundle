<?php
/**
 * @copyright Novactive
 * Date: 17/05/2022
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field\Compose;

trait Identifier
{
    public function getIdentifier(): ?string
    {
        return $this->getOption('identifier', '');
    }

    public function setIdentifier($identifier): self
    {
        $this->setOption('identifier', $identifier);

        return $this;
    }
}
