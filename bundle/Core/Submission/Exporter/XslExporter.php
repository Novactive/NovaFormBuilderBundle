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
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\File\File;

class XslExporter implements ExporterInterface
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
        return 'xls';
    }

    public function generateFile(array $headers, array $submissions): File
    {
        $spreadsheet = new Spreadsheet();
        $worksheet   = $spreadsheet->getSheet(0);
        $headerIndex = 'A';
        foreach ($headers as $header) {
            $worksheet->setCellValue("{$headerIndex}1", $header);
            ++$headerIndex;
        }

        $rowIndex = 1;
        foreach ($submissions as $submission) {
            ++$rowIndex;
            $columnIndex     = 'A';
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
                $worksheet->setCellValue("$columnIndex{$rowIndex}", $value);
                ++$columnIndex;
            }
        }

        $spreadsheet->getActiveSheet()->getStyle("A1:{$headerIndex}1")->getFont()->setBold(true);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $dest   = tempnam(sys_get_temp_dir(), uniqid('submissions', true)).'.xlsx';
        $writer->save($dest);

        return new File($dest);
    }
}
