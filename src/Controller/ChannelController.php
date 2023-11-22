<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Repository\ChannelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/channel')]
class ChannelController extends AbstractController
{
    #[Route('/show/{id}', name: 'app_channel')]
    public function showChannel(Channel $channel): Response
    {

        return $this->json($channel,200,[],["groups"=>"forChannel"]);
    }


    #[Route('/showAll')]
    public function indexChannels(ChannelRepository $repository):Response{

        return $this->json($repository->findAll(),200);
    }

    public function createChannel():Response{

    }


}
