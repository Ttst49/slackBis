<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\ChannelMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/channel/message')]
class ChannelMessageController extends AbstractController
{
    #[Route('/show/{id}', methods: "GET")]
    public function showChannelMessage(ChannelMessage $channelMessage): Response
    {

        $channelMembers = $channelMessage->getAssociatedToChannel()->getChannelMembers();
        foreach ($channelMembers as $member){
            if ($member != $this->getUser()->getProfile()){
                return $this->json("Rejoignez ce channel avant de voir ses messages",200);
            }
        }


        return $this->json($channelMessage,200,[],["groups"=>"forChannelMessages"]);
    }



    #[Route('/index/{id}',methods: "GET")]
    public function showAllMessagesFromChannel(Channel $channel):Response{

        $messages = $channel->getChannelMessages();

        return $this->json($messages,200,[],["groups"=>"forChannelMessages"]);
    }


    #[Route('/create/{id}',methods: "POST")]
    public function createChannelMessage(Channel $channel, SerializerInterface $serializer, EntityManagerInterface $manager,Request $request):Response{

        foreach ($channel->getChannelMembers() as $member){
            if ($this->getUser()->getProfile() === $member){
                $newMessage = $serializer->deserialize($request->getContent(),ChannelMessage::class, "json");
                $newMessage->setAuthor($this->getUser()->getProfile());
                $newMessage->setAssociatedToChannel($channel);
                $manager->persist($newMessage);
                $manager->flush();
                return $this->json($newMessage,201,[],["groups"=>"forChannelMessages"]);
            }
        }
        return $this->json("Vous ne semblez pas membre de ce channel",200);
    }



    #[Route('/delete/{id}')]
    public function deleteChannelMessage(ChannelMessage $channelMessage, EntityManagerInterface $manager):Response{

        if ($channelMessage->getAuthor() == $this->getUser()->getProfile()){
            $manager->remove($channelMessage);
            $manager->flush();
            return $this->json("Le message a bien été supprimé du channel",200);
        }

        return $this->json("Vous ne semlbez pas être l'auteur de ce message",200);
    }
}
