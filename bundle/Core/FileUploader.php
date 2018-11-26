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

namespace Novactive\Bundle\FormBuilderBundle\Core;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileUploader implements FileUploaderInterface
{
    public const TARGET_DIR = '/tmp';

    public function upload(UploadedFile $file): string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName         = md5(uniqid($originalFileName, true)).'.'.$file->guessExtension();

        try {
            $file->move(self::TARGET_DIR, $fileName);
        } catch (FileException $e) {
            throw new NotFoundHttpException('Failed to upload the file: '.$e->getMessage());
        }

        return $fileName;
    }

    public function getFile(string $fileName): Response
    {
        $filePath = self::TARGET_DIR.'/'.$fileName;
        if (!file_exists($filePath)) {
            return new Response('File not found.');
        }

        $response = new Response(file_get_contents($filePath));

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
