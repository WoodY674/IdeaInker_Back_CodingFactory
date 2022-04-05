<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\DeserializeListener as DecoratedListener;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Controller\UploadImageController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DeserializeListener {
    private DecoratedListener $decorated;
    private SerializerContextBuilderInterface $serializerContextBuilder;
    private DenormalizerInterface $denormalizer;
    private UploadImageController $uploadImageController;

    public function __construct(DecoratedListener $decorated,
                                SerializerContextBuilderInterface $serializerContextBuilder,
                                DenormalizerInterface $denormalizer,
                                UploadImageController $uploadImageController) {
        $this->decorated = $decorated;
        $this->serializerContextBuilder = $serializerContextBuilder;
        $this->denormalizer = $denormalizer;
        $this->uploadImageController = $uploadImageController;
    }

    public function onKernelRequest(RequestEvent $event): void {
        $request = $event->getRequest();
        if($request->isMethodCacheable()|| $request->isMethod(Request::METHOD_DELETE)) {
            return;
        }
        if($request->getContentType() === 'multipart') {
            $this->denormalizeFromRequest($request);
        } else {
            $this->decorated->onKernelRequest($event);
        }
    }

    private function denormalizeFromRequest(Request $request) {
        $attributes = RequestAttributesExtractor::extractAttributes($request);
        if(empty($attributes)) {
            return;
        }
        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);
        $populated = $request->attributes->get('data');
        if($populated !== null) {
            $context['object_to_populate'] = $populated;
        }
        $data = $request->request->all();
        $allFiles = $request->files->all();
        $files = $this->uploadImageController->settingImage($allFiles);
        $object = $this->denormalizer->denormalize(
            $data,
            $attributes['resource_class'],
            null,
            $context
        );
        $object->setImage($files['image']);
        $request->attributes->set('data', $object);
    }
}