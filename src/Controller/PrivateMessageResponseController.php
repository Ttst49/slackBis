<?php

namespace App\Controller;

use App\Entity\PrivateMessage;
use App\Entity\PrivateMessageResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/private/response')]
class PrivateMessageResponseController extends AbstractController
{
    #[Route('/send/{id}',methods: "POST")]
    public function sendPrivateMessageResponse(PrivateMessage $message, EntityManagerInterface $manager, SerializerInterface $serializer,Request $request):Response{


        if($message->getAssociatedToConversation()->getRelatedToProfileB() == $this->getUser()->getProfile()
            or $message->getAssociatedToConversation()->getRelatedToProfileA() == $this->getUser()->getProfile()){
            $response = $serializer->deserialize($request->getContent(),PrivateMessageResponse::class,"json");
            $response->setAuthor($this->getUser()->getProfile());
            $response->setRelatedToPrivateMessage($message);
            $manager->persist($response);
            $manager->flush();
            return $this->json($response,201,[],["groups"=>"forPrivateConversation"]);

        }

        return $this->json("Vous ne pouvez pas faire ça",200);
    }


    #[Route('/remove/{id}',methods: "DELETE")]
    public function removePrivateMessageResponse(PrivateMessageResponse $response, EntityManagerInterface $manager):Response{


        if ($response->getAuthor() == $this->getUser()->getProfile()){
            $manager->remove($response);
            $manager->flush();
            return $this->json("Votre reponse a bien été supprimée",200);
        }

        return $this->json("Vous ne semblez pas être l'auteur de cette réponse",200);
    }


    #[Route('/edit/{id}',methods: "PUT")]
    public function editPrivateMessageResponse(SerializerInterface $serializer,PrivateMessageResponse $response, EntityManagerInterface $manager, Request $request):Response{

        if ($response->getAuthor() == $this->getUser()->getProfile()){
            $response->setDate(new \DateTime());
            $responseDeserialized = $serializer->deserialize($request->getContent(),PrivateMessageResponse::class,"json",array("object_to_populate"=>$response));
            $manager->persist($responseDeserialized);
            $manager->flush();
            return $this->json($responseDeserialized,200,[],["groups"=>"forPrivateConversation"]);
        }

        return $this->json("Vous ne semblez pas être l'auteur de cette réponse",200);
    }

}
