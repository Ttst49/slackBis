<?php

namespace App\Controller;

use App\Entity\GroupMessage;
use App\Entity\GroupMessageResponse;
use App\Entity\PrivateMessage;
use App\Entity\PrivateMessageResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/group/response')]
class GroupMessageResponseController extends AbstractController
{

    #[Route('/send/{id}')]
    public function sendPrivateMessageResponse(GroupMessage $message, EntityManagerInterface $manager, SerializerInterface $serializer,Request $request):Response{

        foreach ($message->getGroupConversation()->getGroupMembers() as $member){
            if ($member == $this->getUser()->getProfile()){
                $response = $serializer->deserialize($request->getContent(),GroupMessageResponse::class,"json");
                $response->setAuthor($this->getUser()->getProfile());
                $response->setRelatedToGroupMessage($message);
                $manager->persist($response);
                $manager->flush();
                return $this->json($response,201,[],["groups"=>"forGroupIndexing"]);

            }
        }


        return $this->json("Vous ne pouvez pas faire ça",200);
    }

}
