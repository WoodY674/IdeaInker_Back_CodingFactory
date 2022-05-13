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

    /**
     * convert base64 to entity image by extracting the data
     * @param array|string $rawImage64
     * @return Image|array|null
     */
    public function convertImages64ToEntity(array|string $rawImage64) {
        if (is_array($rawImage64)) {
            $images = [];
            foreach ($rawImage64 as $image64) {
                $images[] = $this->imageString64($image64);
            }
            return $images;
        } elseif (is_string($rawImage64)) {
            return $this->imageString64($rawImage64);
        }
        return null;
    }

    /**
     * the function extract the data of the file (in base64)
     * and explode the data for the type of file
     * creating a file with a base64 and set the name
     * create new image and put the file in the setter
     * @param string $image64
     * @return Image
     */
    public function imageString64(string $image64) {
        $base64Image = $this->base64FileExtractor->extractBase64String($image64);
        $formatType = explode('/', $base64Image[0]);
        $imageFile = new UploadedBase64File($base64Image[1], $this->setUniqueName() . "." . $formatType[1]);
        $image = new Image();
        $image->setImageFile($imageFile);

        return $image;
    }

    /**
     * make a unique name with a default name
     * @param string $name
     * @return string
     */
    private function setUniqueName(string $name = "JeSuisUnNomSuperUnique") {
        return str_shuffle($name.uniqid());
    }
}