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
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use eZ\Publish\API\Repository\ContentService;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\File\File;

class FormService
{
    /** @var Connection */
    private $connection;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * FormService constructor.
     */
    public function __construct(
        Connection $connection,
        EntityManagerInterface $entityManager,
        ContentService $contentService
    ) {
        $this->connection     = $connection;
        $this->entityManager  = $entityManager;
        $this->contentService = $contentService;
    }

    public function save(Form $formEntity): int
    {
        $originalFields = new ArrayCollection();
        foreach ($formEntity->getFields() as $field) {
            $originalFields->add($field);
        }
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

    public function associatedContents(Form $form)
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('o.id')
            ->from('ezcontentobject', 'o')
            ->innerJoin(
                'o',
                'ezcontentobject_attribute',
                'oa',
                $query->expr()->andX(
                    $query->expr()->eq('o.id', 'oa.contentobject_id'),
                    $query->expr()->eq('o.current_version', 'oa.version')
                )
            )
            ->where(
                $query->expr()->andX(
                    $query->expr()->eq('oa.data_type_string', ':data_type_string'),
                    $query->expr()->eq('oa.data_int', ':form_id')
                )
            )
            ->setParameter(':data_type_string', 'ezcustomform', Type::STRING)
            ->setParameter(':form_id', $form->getId(), Type::INTEGER);

        $contentsId = $query->execute()->fetchAll(FetchMode::COLUMN);
        $contents   = [];
        foreach ($contentsId as $contentId) {
            $contents[] = $this->contentService->loadContent($contentId);
        }

        return $contents;
    }
}
