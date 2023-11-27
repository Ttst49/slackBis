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



}
