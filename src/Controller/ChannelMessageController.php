<?php

namespace App\Controller;

use App\Entity\ChannelMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/channel/message/')]
class ChannelMessageController extends AbstractController
{
    #[Route('/show/{id}', name: 'app_channel_message')]
    public function showChannelMessage(ChannelMessage $channelMessage): Response
    {

        $channelMembers = $channelMessage->getAssociatedToChannel()->getChannelMembers();
        foreach ($channelMembers as $member){
            if ($member != $this->getUser()->getProfile()){
                return $this->json("Rejoignez ce channel avant de voir ses messages",200);
            }
        }


        return $this->json($channelMessage,200);
    }
}
