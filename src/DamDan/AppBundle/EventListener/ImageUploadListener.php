<?php
namespace DamDan\AppBundle\EventListener;
use DamDan\AppBundle\Entity\Dish;
use DamDan\AppBundle\Services\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Class RecipeUploadListener
 * @package DamDan\AppBundle\EventListener
 * @author Daniel Cole <daniel@dancole.fr>
 */
class ImageUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        dump($entity);
        $this->uploadFile($entity);
    }

    /**
     * Upload File
     *
     * @param $entity
     */
    private function uploadFile($entity)
    {

        // upload only works for Dish entities
        if (!$entity instanceof Dish) {
            return;
        }

        $file = $entity->getFile();



        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);
        $entity->setImage($fileName);
    }
}
