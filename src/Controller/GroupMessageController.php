<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\GroupMessage;
use App\Repository\GroupMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/group/message')]
class GroupMessageController extends AbstractController
{
    #[Route('/send/{id}',methods: "POST")]
    public function sendMessage(GroupConversation $groupConversation, SerializerInterface $serializer, EntityManagerInterface $manager, Request $request): Response
    {
        foreach ($groupConversation->getGroupMembers() as $member){
            if ($this->getUser()->getProfile() === $member) {
                $groupMessage = $serializer->deserialize($request->getContent(),GroupMessage::class,"json");
                $groupMessage->setAuthor($this->getUser()->getProfile());
                $groupMessage->setGroupConversation($groupConversation);
                $manager->persist($groupMessage);
                $manager->flush();
                return $this->json($groupMessage,201,[],["groups"=>"forGroupIndexing"]);
            }
        }


        return $this->json("Vous ne faites pas parti de ce groupe visiblement ou il n'existe pas",404);
    }


    #[Route('/show/{id}',methods: "GET")]
    public function showGroupMessage(GroupMessage $groupMessage):Response{

        foreach($groupMessage->getGroupConversation()->getGroupMembers() as $member){
            if ($this->getUser()->getProfile() === $member){
                return $this->json($groupMessage,200,[],["groups"=>"forGroupIndexing"]);
            }
        }
        return $this->json("Rien à montrer",200);
    }


    #[Route('/delete/{id}',methods: "DELETE")]
    public function deleteGroupMessage(GroupMessage $message, GroupMessageRepository $repository, EntityManagerInterface $manager):Response{



        if ($message->getAuthor() == $this->getUser()->getProfile()){
            $message->setContent("Message supprimé");
            $manager->persist($message);
            $manager->flush();
            return $this->json("Le message a bien été supprimé",200);
        }

        return $this->json("Vous ne semblez pas être l'auteur de ce message",200);
    }


    #[Route('/edit/{id}',methods: "PUT")]
    public function editGroupMessage(GroupMessage $message, SerializerInterface $serializer, Request $request, EntityManagerInterface $manager):Response{

        if ($message->getAuthor() == $this->getUser()->getProfile()){
            $messageDeserialized = $serializer->deserialize($request->getContent(),GroupMessage::class,"json",array("object_to_populate"=>$message));
            $manager->persist($messageDeserialized);
            $manager->flush();
            return $this->json($messageDeserialized,200,[],["groups"=>"forGroupIndexing"]);
        }
        return $this->json("Vous ne pouvez pas modifier un message dont vous n'êtes pas l'auteur",200);
    }


}
