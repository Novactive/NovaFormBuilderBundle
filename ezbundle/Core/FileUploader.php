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

use Novactive\Bundle\FormBuilderBundle\Core\FileUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileUploader implements FileUploaderInterface
{
    /**
     * @var IOService
     */
    private $ioService;

    /**
     * FileUploader constructor.
     */
    public function __construct(IOService $ioService)
    {
        $this->ioService = $ioService;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName         = md5(uniqid($originalFileName, true)).'.'.$file->guessExtension();
        $fileContents     = file_get_contents($file->getPathname());

        return $this->ioService->saveFile($fileName, $fileContents);
    }

    public function getFile(string $fileName): Response
    {
        if (!$this->ioService->fileExists($fileName)) {
            return new Response('File not found.');
        }

        $response = new Response($this->ioService->readFile($fileName));

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $fileName
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', 'application/octet-stream');

        return $response;
    }
}
