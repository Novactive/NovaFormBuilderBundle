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

namespace Novactive\Bundle\FormBuilderBundle\Entity\Field;

use Doctrine\ORM\Mapping as ORM;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;

/**
 * @ORM\Entity
 */
class Email extends Field
{
    public function isSendData(): bool
    {
        return (bool) ($this->getOption('send_data') ?? false);
    }

    public function setSendData(bool $sendData): self
    {
        $this->setOption('send_data', $sendData);

        return $this;
    }
}
