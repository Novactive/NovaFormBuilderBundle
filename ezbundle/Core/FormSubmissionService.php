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

namespace Novactive\Bundle\eZFormBuilderBundle\Core;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use IntlDateFormatter;
use Novactive\Bundle\FormBuilderBundle\Core\FieldTypeRegistry;
use Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission;
use Symfony\Component\Translation\TranslatorInterface;

class FormSubmissionService
{
    /** @var FieldTypeRegistry */
    private $fieldTypeRegistry;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * FormSubmissionService constructor.
     */
    public function __construct(FieldTypeRegistry $fieldTypeRegistry, TranslatorInterface $translator)
    {
        $this->fieldTypeRegistry = $fieldTypeRegistry;
        $this->translator        = $translator;
    }

    public function getExportableDatas(FormSubmission $formSubmission): array
    {
        $dateFormatter = IntlDateFormatter::create('', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);

        $exportData = [
            [
                'name'  => $this->translator->trans('form.submission.date', [], 'novaformbuilder'),
                'value' => $dateFormatter->format($formSubmission->getCreatedAt()->getTimestamp()),
                'type'  => 'date',
            ],
        ];
        $data       = $formSubmission->getData();
        foreach ($data as $data) {
            try {
                $fieldType = $this->fieldTypeRegistry->getFieldTypeByIdentifier($data['type']);
                $canExport = $fieldType->canExport();
            } catch (InvalidArgumentException $e) {
                $canExport = true;
            }
            if (true === $canExport) {
                $exportData[] = $data;
            }
        }

        return $exportData;
    }

    public function sortSubmissionDatas(array $datas, array $headers = []): array
    {
        $sortedDatas = [];
        foreach ($headers as $header) {
            $foundData = null;
            foreach ($datas as $k => $data) {
                if ($data['name'] === $header) {
                    $foundData = $data;
                    unset($datas[$k]);
                    break;
                }
            }

            $sortedDatas[] = $foundData;
        }
        foreach ($datas as $data) {
            $sortedDatas[] = $data;
        }

        return $sortedDatas;
    }
}
