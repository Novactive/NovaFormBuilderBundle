<?php
/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @copyright 2020 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Core\Submission\Exporter;

use Symfony\Component\HttpFoundation\File\File;

interface ExporterInterface
{
    public function generateFile(array $headers, array $submissions): File;

    public function getType(): string;
}
