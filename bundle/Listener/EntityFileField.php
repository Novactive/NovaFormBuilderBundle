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

namespace Novactive\Bundle\FormBuilderBundle\Listener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Novactive\Bundle\FormBuilderBundle\Core\FileUploader;
use Novactive\Bundle\FormBuilderBundle\Entity\Field\File;

class EntityFileField
{
    /**
     * @var FileUploader
     */
    private $fileUploader;

    public function setUploader(FileUploader $fileUploader): void
    {
        $this->fileUploader = $fileUploader;
    }

//    public function postPersist(LifecycleEventArgs $args): void
//    {
//        $entity = $args->getObject();
//        dump($entity);
//        //exit;
//        // only act on some "Product" entity
//        if ($entity instanceof File) {
//            dump($entity);
//            exit;
//        }
//
//    }
}
