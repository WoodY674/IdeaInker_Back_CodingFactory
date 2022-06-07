<?php

namespace App\Controller;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;

class UploadImageController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settingImage($files)
    {
        $data = [];
        if (!empty($files['image'])) {
            if (is_array($files['image'])) {
                foreach ($files['image'] as $file) {
                    $image = new Image();
                    $image->setImageFile($file);
                    $this->entityManager->persist($image);
                    $data[] = $image;
                }
                $files['image'] = $data;
            } else {
                $image = new Image();
                $image->setImageFile($files['image']);
                $this->entityManager->persist($image);
                $files['image'] = $image;
            }
        }

        return $files;
    }
}
