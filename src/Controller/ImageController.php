<?php

namespace App\Controller;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ImageController extends AbstractController
{
    #[Route('/image', name: 'app_image')]
    public function addImage(Request $request, EntityManagerInterface $manager): Response
    {

        $requestedImage = $request->files->get('image');
        if ($requestedImage){
            $image = new Image();
            $image->setImageFile($requestedImage);
            $image->setUploadedBy($this->getUser()->getProfile());
            $manager->persist($image);

            $manager->flush();

            $response = [
                "Vous avez bien uploader l'image d'id".$image->getId()


            ];

            return $this->json($image,201,[],["groups"=>"forImageIndexing"]);
        }

        return $this->json("Aucune image a charger",404);
    }
}
