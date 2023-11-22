<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\ImagePostProcessing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ImageController extends AbstractController
{
    #[Route('/image', methods: "POST")]
    public function addImage(Request $request, EntityManagerInterface $manager,ImagePostProcessing $postProcessing): Response
    {

        $requestedImage = $request->files->get('image');
        if ($requestedImage){
            $image = new Image();
            $image->setImageFile($requestedImage);
            $image->setUploadedBy($this->getUser()->getProfile());
            $manager->persist($image);
            $manager->flush();

            $postProcessedImage = $postProcessing->getThumbnailUrlFromImage($image);

            $response =  [
                "status"=>"L'image a bien été ajouté et peut être utilisé dans vos messages",
                "imageId"=>$postProcessedImage["id"],
                "imageUrl"=>$postProcessedImage["url"]
            ];


            return $this->json($response,201,[],["groups"=>"forImageIndexing"]);
        }

        return $this->json("Aucune image a charger",404);
    }
}
