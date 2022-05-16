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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use eZ\Publish\API\Repository\ContentService;
use Novactive\Bundle\FormBuilderBundle\Core\FieldTypeRegistry;
use Novactive\Bundle\FormBuilderBundle\Core\Submission\Exporter\ExporterRegistry;
use Novactive\Bundle\FormBuilderBundle\Entity\Field;
use Novactive\Bundle\FormBuilderBundle\Entity\Form;
use Novactive\Bundle\FormBuilderBundle\Entity\FormSubmission;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Translation\Translator;

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
     * @var FieldTypeRegistry
     */
    private $fieldTypeRegistry;

    /**
     * @var ExporterRegistry
     */
    private $exporterRegistry;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * FormService constructor.
     */
    public function __construct(
        Connection $connection,
        EntityManagerInterface $entityManager,
        ContentService $contentService,
        FieldTypeRegistry $fieldTypeRegistry,
        ExporterRegistry $exporterRegistry,
        Translator $translator
    ) {
        $this->connection        = $connection;
        $this->entityManager     = $entityManager;
        $this->contentService    = $contentService;
        $this->fieldTypeRegistry = $fieldTypeRegistry;
        $this->exporterRegistry  = $exporterRegistry;
        $this->translator        = $translator;
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

    public function generateSubmissions(Form $form, string $type): File
    {
        $headers = [
            $this->translator->trans('form.submission.date', [], 'novaformbuilder'),
        ];
        foreach ($form->getFields() as $field) {
            $fieldType = $this->fieldTypeRegistry->getFieldTypeByIdentifier($field->getType());
            if (true === $fieldType->canExport()) {
                $headers[] = $field->getName();
            }
        }

        $exporter = $this->exporterRegistry->getExporterByType($type);

        return $exporter->generateFile($headers, $form->getSubmissions()->getValues());
    }

    public function associatedContents(int $formId)
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
            ->setParameter(':form_id', $formId, Type::INTEGER);

        $contentsId = $query->execute()->fetchAll(FetchMode::COLUMN);
        $contents   = [];
        foreach ($contentsId as $contentId) {
            $contents[] = $this->contentService->loadContent($contentId);
        }

        return $contents;
    }

    public function generateSubmissionsByFilter(array $filter)
    {
        /** @var EntityManager $em */
        $em   = $this->entityManager;
        $form = $em->find(Form::class, $filter['form']);

        $submissions = $em
            ->getRepository(FormSubmission::class)
            ->createQueryBuilder('fs')
            ->where('fs.form = :form')
            ->andWhere('fs.createdAt BETWEEN :start_date AND :end_date')
            ->setParameters(
                [
                    'form'       => $filter['form'],
                    'start_date' => $filter['start_date'],
                    'end_date'   => $filter['end_date'],
                ]
            )
            ->getQuery()
            ->getResult();

        $headers = [
            $this->translator->trans('form.submission.date', [], 'novaformbuilder'),
        ];
        foreach ($form->getFields() as $field) {
            $fieldType = $this->fieldTypeRegistry->getFieldTypeByIdentifier($field->getType());
            if (true === $fieldType->canExport()) {
                $headers[] = $field->getName();
            }
        }

        $exporter = $this->exporterRegistry->getExporterByType($filter['export_type']);

        return $exporter->generateFile($headers, $submissions);
    }
}
