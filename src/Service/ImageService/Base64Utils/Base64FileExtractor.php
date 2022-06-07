<?php

namespace App\Service\ImageService\Base64Utils;

class Base64FileExtractor
{
    /**
     * extract the data URI from base64 for the type.
     *
     * @return string[]
     */
    public function extractBase64String(string $base64Content)
    {
        return explode(';base64,', $base64Content);
    }
}
