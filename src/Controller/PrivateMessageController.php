<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\PrivateMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/private/message')]
class PrivateMessageController extends AbstractController
{
    #[Route('/send/{id}', name: 'app_private_message')]
    public function sendPrivateMessage(PrivateConversation $privateConversation,SerializerInterface $serializer,Request $request,EntityManagerInterface $manager): Response
    {
        if ($this->getUser() === $privateConversation->getRelatedToProfileB()->getRelatedTo()
            or $this->getUser() === $privateConversation->getRelatedToProfileA()->getRelatedTo()){
            $privateMessage = $serializer->deserialize($request->getContent(),PrivateMessage::class,"json");
            $privateMessage->setDate(new \DateTime());
            $privateMessage->setAuthor($this->getUser()->getProfile());
            $privateMessage->setAssociatedToConversation($privateConversation);
            $manager->persist($privateMessage);
            $manager->flush();

            return $this->json($privateMessage->getContent(),200);
        }

        return $this->json("Vous ne pouvez pas faire cela",200);
    }

    #[Route('/remove/{id}',methods: "DELETE")]
    public function removePrivateMessage(PrivateMessage $privateMessage,EntityManagerInterface $manager):Response{

        if ($privateMessage->getAuthor() == $this->getUser()->getProfile()){
            $manager->remove($privateMessage);
            $manager->flush();
            return $this->json("Votre message a bien été supprimé",200);
        }
        return $this->json("Vous n'êtes pas l'auteur de ce message",200);
    }


    #[Route('/edit/{id}',methods: 'PUT')]
    public function editPrivateMessage(PrivateMessage $privateMessage,EntityManagerInterface $manager, Request $request):Response{

        if ($privateMessage->getAuthor() == $this->getUser()->getProfile()){
            $privateMessage->setDate(new \DateTime());
            $content = json_decode($request->getContent(),true);
            $privateMessage->setContent($content['content']);
            $manager->persist($privateMessage);
            $manager->flush();
            return $this->json($privateMessage->getContent(),200);
        }
        return $this->json("Vous ne pouvez pas modifier un message dont vous n'êtes pas l'auteur",200);
    }
}
