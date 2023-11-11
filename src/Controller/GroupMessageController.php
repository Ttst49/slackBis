<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\GroupMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/group/message')]
class GroupMessageController extends AbstractController
{
    #[Route('/send/{id}', name: 'app_group_message')]
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
}
