<?php

/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Maxim Strukov <m.strukov@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\eZFormBuilderBundle\Core;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\File\File;

class FormService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(ArrayCollection $originalFields, Form $formEntity): int
    {
        foreach ($originalFields as $field) {
            /** @var Field $field */
            if (!$formEntity->getFields()->contains($field)) {
                $field->setForm(null);
                $this->entityManager->persist($field);
                $this->entityManager->remove($field);
            }
        }
        $this->entityManager->persist($formEntity);
        $this->entityManager->flush();

        return $formEntity->getId();
    }

    public function removeForm(Form $formEntity): void
    {
        /** @var Field $field */
        foreach ($formEntity->getFields() as $field) {
            $field->setForm(null);
            $this->entityManager->persist($field);
            $this->entityManager->remove($field);
        }
        $this->entityManager->remove($formEntity);
        $this->entityManager->flush();
    }

    public function generateSubmissionsXls(Form $form): File
    {
        $spreadsheet = new Spreadsheet();
        $worksheet   = $spreadsheet->getSheet(0);
        $headerIndex = 'A';
        foreach ($form->getFields() as $field) {
            $worksheet->setCellValue("{$headerIndex}1", $field->getName());
            ++$headerIndex;
        }

        $rowIndex = 1;
        foreach ($form->getSubmissions() as $submission) {
            ++$rowIndex;
            $columnIndex = 'A';
            foreach ($submission->getData() as $item) {
                $value = $item['value'];

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
