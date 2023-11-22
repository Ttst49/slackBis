<?php

namespace App\Service;

use App\Entity\GroupMessage;
use App\Entity\Image;
use App\Entity\PrivateMessage;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
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

    public function getImagesUrlFromImages(PrivateMessage $privateMessage): PrivateMessage
    {

        $imageUrls = new ArrayCollection();


        foreach ($privateMessage->getImages() as $image) {
            $imageFound = $this->imageRepository->find($image);
            if ($imageFound){
                $newImageURL = ["id"=>$imageFound->getId(), "url"=>$this->cacheManager->getBrowserPath($this->uploaderHelper->asset($imageFound),"thumbnail")];
                $imageUrls->add($newImageURL);
            }
        }
        $privateMessage->setImagesUrls($imageUrls);

        return $privateMessage;
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



    public function getThumbnailUrlFromImage(Image $image){


        return ["id"=>$image->getId(), "url"=>$this->cacheManager->getBrowserPath($this->uploaderHelper->asset($image),"thumbnail")];
    }


}