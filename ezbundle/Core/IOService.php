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

use eZ\Publish\Core\IO\IOServiceInterface;
use Symfony\Component\Filesystem\Filesystem;

class IOService
{
    /**
     * @var IOServiceInterface
     */
    private $io;

    public function __construct(IOServiceInterface $io)
    {
        $this->io = $io;
    }

    public function saveFile(string $name, string $content): string
    {
        $fs = new Filesystem();

        $temporaryPath = tempnam(sys_get_temp_dir(), uniqid($name, true));
        $fs->dumpFile($temporaryPath, $content);
        $uploadedFileStruct     = $this->io->newBinaryCreateStructFromLocalFile($temporaryPath);
        $uploadedFileStruct->id = $name.'.json';
        $ioFile                 = $this->io->createBinaryFile($uploadedFileStruct);
        $fs->remove($temporaryPath);

        return $ioFile->id;
    }

    public function readFile(string $filename): string
    {
        $file = $this->io->loadBinaryFile($filename);

        return $this->io->getFileContents($file);
    }
}
