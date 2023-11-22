<?php

namespace App\Service;

use App\Entity\GroupMessage;
use App\Entity\PrivateMessage;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImagePostProcessing
{

    private ImageRepository $imageRepository;
    private CacheManager $cacheManager;
    private UploaderHelper $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper, CacheManager $cacheManager,ImageRepository $repository){
        $this->imageRepository = $repository;
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getImagesUrlFromImages(array $images): ArrayCollection
    {

        $imageUrls = new ArrayCollection();

        foreach ($images as $image) {
            $imageFound = $this->imageRepository->find($image);
            if ($imageFound){
                $newImageURL = $this->cacheManager->getBrowserPath($this->uploaderHelper->asset($imageFound),"my_thumb");
                $imageUrls->add($newImageURL);
            }
        }

        return $imageUrls;
    }

    public function getImagesFromIds(array $imageIds):array{

        $images = [];
        foreach ($imageIds as $id){
            $imageFound = $this->imageRepository->find($id);
            if ($imageFound){
                $images[] = $imageFound;
            }
        }

        return $images;
    }


}