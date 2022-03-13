<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class ImageController
{
    public function __invoke(Request $request) {
        $files = $request->files->get('files');
        dd($files);
        // TODO: Implement __invoke() method.
    }
}