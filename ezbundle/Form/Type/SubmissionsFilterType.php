<?php

declare(strict_types=1);

namespace Novactive\Bundle\eZFormBuilderBundle\Form\Type;

use Novactive\Bundle\FormBuilderBundle\Core\Submission\Exporter\ExporterRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SubmissionsFilterType extends AbstractType
{
    /**
     * @var ExporterRegistry
     */
    private $exporterRegistry;

    public function __construct(ExporterRegistry $exporterRegistry)
    {
        $this->exporterRegistry = $exporterRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $exportTypes = array_combine(
            $this->exporterRegistry->getExporterTypes(),
            $this->exporterRegistry->getExporterTypes()
        );
        $builder
            ->add('form', HiddenType::class)
            ->add(
                'start_date',
                DateType::class,
                [
                    'label'              => 'theme.submissions.download_submissions.start_date',
                    'required'           => false,
                    'widget'             => 'single_text',
                    'translation_domain' => 'novaezformbuilder',
                ]
            )
            ->add(
                'end_date',
                DateType::class,
                [
                    'label'              => 'theme.submissions.download_submissions.end_date',
                    'required'           => false,
                    'widget'             => 'single_text',
                    'translation_domain' => 'novaezformbuilder',
                ]
            )
            ->add(
                'export_type',
                ChoiceType::class,
                [
                    'label'              => 'theme.submissions.download_submissions.export_type',
                    'choices'            => $exportTypes,
                    'translation_domain' => 'novaezformbuilder',
                ]
            )
            ->add(
                'export',
                SubmitType::class,
                [
                    'label'              => 'theme.submissions.download_submissions.export',
                    'translation_domain' => 'novaezformbuilder',
                ]
            );
    }

    public function getBlockPrefix(): string
    {
        return 'ezplatform_fieldtype_submissions_filter';
    }
}
