<?php

namespace App\Service\ImageService;

use App\Entity\Image;
use App\Service\ImageService\Base64Utils\Base64FileExtractor;
use App\Service\ImageService\Base64Utils\UploadedBase64File;

class ImageCreatorService {
    private Base64FileExtractor $base64FileExtractor;

    public function __construct(Base64FileExtractor $base64FileExtractor) {
        $this->base64FileExtractor = $base64FileExtractor;
    }

    public function convertImages64ToEntity(array|string $rawImage64) {
        if (is_array($rawImage64)) {
            return $this->imagesArray64($rawImage64);
        } elseif (is_string($rawImage64)) {
            return $this->imageString64($rawImage64);
        }
    }

    public function imageString64(string $image64) {
        $base64Image = $this->base64FileExtractor->extractBase64String($image64);
        $formatType = explode('/', $base64Image[0]);
        $imageFile = new UploadedBase64File($base64Image[1], $this->setUniqueName() . "." . $formatType[1]);
        $image = new Image();
        $image->setImageFile($imageFile);

        return $image;
    }

    public function imagesArray64(array $rawImage64) {
        $images = [];
        foreach ($rawImage64 as $image64) {
            $base64Image = $this->base64FileExtractor->extractBase64String($image64);
            $imageFile = new UploadedBase64File($base64Image, $this->setUniqueName());

            $image = new Image();
            $image->setImageFile($imageFile);
            $images[] = $image;
        }
        return $images;
    }
    private function setUniqueName(string $name = "JeSuisUnNomSuperUnique") {
        return str_shuffle($name.uniqid());
    }
}