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

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;

class ExporterRegistry
{
    /**
     * @var ExporterInterface[]
     */
    private $exporters = [];

    public function __construct(iterable $exporters)
    {
        foreach ($exporters as $exporter) {
            $this->registerExporter($exporter);
        }
    }

    public function registerExporter(ExporterInterface $exporter): void
    {
        $this->exporters[$exporter->getType()] = $exporter;
    }

    public function getExporterTypes(): array
    {
        return array_keys($this->exporters);
    }

    /**
     * @return ExporterInterface[]
     */
    public function getExporters(): array
    {
        return $this->exporters;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getExporterByType(string $type): ExporterInterface
    {
        if (!isset($this->exporters[$type])) {
            $msg = sprintf('Form submisson exporter with identifier "%s" doesn\'t exist', $type);
            throw new InvalidArgumentException('type', $msg);
        }

        return $this->exporters[$type];
    }
}
