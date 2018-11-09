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

namespace Novactive\Bundle\eZFormBuilderBundle\Core\Generator;

use Knp\Snappy\GeneratorInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Pdf.
 */
class Pdf
{
    /**
     * The PDF Converter.
     *
     * @var GeneratorInterface
     */
    private $pdfConverter;

    /**
     * Pdf constructor.
     */
    public function __construct(GeneratorInterface $pdfConverter)
    {
        $this->pdfConverter = $pdfConverter;
    }

    public function generate(string $html, array $options = []): File
    {
        $tmpFilePath = sys_get_temp_dir().'/'.uniqid('submissions', true).'.pdf';
        $this->pdfConverter->generateFromHtml($html, $tmpFilePath, $options, true);

        return new File($tmpFilePath);
    }
}
