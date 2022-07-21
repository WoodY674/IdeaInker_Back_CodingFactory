<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Notice;
use App\Entity\Post;
use App\Entity\Salon;
use App\Entity\User;
use App\Service\ApiService\ApiConstructorService;
use Doctrine\ORM\EntityManagerInterface;

class fetchInformationEntity
{

    private ApiConstructorService $apiService;
    private EntityManagerInterface $entityManager;

    public function __construct(ApiConstructorService $apiService, EntityManagerInterface $entityManager,

    ){
        $this->apiService = $apiService;
        $this->entityManager = $entityManager;
    }

    public function getInformationForPost($post) {
        $metadataPost = $this->entityManager->getMetadataFactory()->getMetadataFor(Post::class);
        $data = $this->apiService->getSimpleDataFromEntity($post, $metadataPost);

        $imagePost = $post->getImage();
        if(isset($imagePost)) {
            $metadataImage = $this->entityManager->getMetadataFactory()->getMetadataFor(Image::class);
            $data[Post::IMAGE] = $this->apiService->getSimpleDataFromEntity($imagePost, $metadataImage);
        } else {
            $data[Post::IMAGE] = null;
        }

        $createdBy = $post->getCreatedBy();
        if(isset($createdBy)) {
            $metadataUser = $this->entityManager->getMetadataFactory()->getMetadataFor(User::class);
            $data[Post::CREATED_BY] = $this->apiService->getSimpleDataFromEntity($createdBy, $metadataUser);
            unset($data[Post::CREATED_BY]['password']);
            unset($data[Post::CREATED_BY]['roles']);
            $imageCreator = $createdBy->getProfileImage();
            if(isset($imageCreator)) {
                $metadataImage = $this->entityManager->getMetadataFactory()->getMetadataFor(Image::class);
                $data[Post::CREATED_BY][User::PROFILE_IMAGE] = $this->apiService->getSimpleDataFromEntity($imageCreator, $metadataImage);
            } else {
                $data[Post::CREATED_BY][User::PROFILE_IMAGE] = null;
            }

        } else {
            $data[Post::CREATED_BY] = null;
        }

        $createdBySalon = $post->getSalon();
        if(isset($createdBySalon)) {
            $metadataUser = $this->entityManager->getMetadataFactory()->getMetadataFor(Salon::class);
            $data[Post::CREATED_BY_SALON] = $this->apiService->getSimpleDataFromEntity($createdBySalon, $metadataUser);
            $imageCreator = $createdBySalon->getSalonImage();
            if(isset($imageCreator)) {
                $metadataImage = $this->entityManager->getMetadataFactory()->getMetadataFor(Image::class);
                $data[Post::CREATED_BY_SALON][Salon::SALON_IMAGE] = $this->apiService->getSimpleDataFromEntity($imageCreator, $metadataImage);
            } else {
                $data[Post::CREATED_BY_SALON][Salon::SALON_IMAGE] = null;
            }

        } else {
            $data[Post::CREATED_BY_SALON] = null;
        }

        return $data;
    }
}