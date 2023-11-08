<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/")]
class RequestController extends AbstractController
{
    #[Route('request/{id}', name: 'app_request')]
    public function sendFriendRequest(Profile $profile, EntityManagerInterface $manager): Response
    {
        $request = new Request();
        $request->setSender($this->getUser()->getProfile());

        $request->setRecipient($profile);


        $manager->persist($request);
        $manager->flush();


        return $this->json("Votre requête a bien été envoyé",200,[],["groups"=>"forCreation"]);
    }

    #[Route("request/get/{id}")]
    public function getRequestInfo(Request $request):Response{

        return $this->json($request,200,[],['groups'=>"forRequest"]);
    }
}
