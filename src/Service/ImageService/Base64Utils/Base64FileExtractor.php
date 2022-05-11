<?php

namespace App\Service\ImageService\Base64Utils;

class Base64FileExtractor {
    public function extractBase64String(string $base64Content)
    {
        return explode( ';base64,', $base64Content);
    }
}