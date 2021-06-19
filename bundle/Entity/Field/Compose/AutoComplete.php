<?php
/**
 * @copyright Novactive
 * Date: 18/06/2021
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field\Compose;

trait AutoComplete
{
    public function getAutoComplete(): ?string
    {
        return $this->getOption('autoComplete');
    }

    public function setAutoComplete(?string $placeholder): void
    {
        $this->setOption('autoComplete', $placeholder);
    }
}
