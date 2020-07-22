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

use Novactive\Bundle\eZFormBuilderBundle\Core\FormSubmissionService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\File\File;

class CsvExporter implements ExporterInterface
{
    /** @var FormSubmissionService */
    private $formSubmissionService;

    /**
     * XslExporter constructor.
     */
    public function __construct(FormSubmissionService $formSubmissionService)
    {
        $this->formSubmissionService = $formSubmissionService;
    }

    public function getType(): string
    {
        return 'csv';
    }

    public function generateFile(array $headers, array $submissions): File
    {
        $spreadsheet = new Spreadsheet();
        $headerLine  = [];
        foreach ($headers as $header) {
            $headerLine[] = $header;
        }
        $lines = [
            $headerLine,
        ];

        foreach ($submissions as $submission) {
            $line            = [];
            $exportableDatas = $this->formSubmissionService->getExportableDatas($submission);
            $exportableDatas = $this->formSubmissionService->sortSubmissionDatas($exportableDatas, $headers);
            foreach ($exportableDatas as $k => $item) {
                $value = $item['value'];
                if ($k >= count($headers)) {
                    $value = sprintf('%s: %s', $item['name'], $value);
                }

                if (\is_array($value)) {
                    $value = isset($value['date']) ? date('F d, Y', strtotime($value['date'])) : implode(', ', $value);
                }
                $line[] = $value;
            }
            $lines[] = $line;
        }

        $dest = tempnam(sys_get_temp_dir(), uniqid('submissions', true)).'.csv';

        $fileResource = fopen($dest, 'w');
        foreach ($lines as $line) {
            fputcsv($fileResource, $line, ';');
        }
        fclose($fileResource);

        return new File($dest);
    }
}
