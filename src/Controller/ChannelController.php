<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Repository\ChannelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/channel')]
class ChannelController extends AbstractController
{
    #[Route('/show/{id}',methods: "GET")]
    public function showChannel(Channel $channel): Response
    {

        return $this->json($channel,200,[],["groups"=>"forChannel"]);
    }


    #[Route('/showAll',methods: "GET")]
    public function indexChannels(ChannelRepository $repository):Response{

        return $this->json($repository->findAll(),200);
    }


    #[Route('/create',methods: "POST")]
    public function createChannel(SerializerInterface $serializer, Request $request,EntityManagerInterface $manager):Response{

        $channel = $serializer->deserialize($request->getContent(),Channel::class,"json");
        $channel->setOwner($this->getUser()->getProfile());
        $channel->addAdminChannelMember($this->getUser()->getProfile()->getRelatedTo());
        $channel->addChannelMember($this->getUser()->getProfile());
        $manager->persist($channel);
        $manager->flush();

        return $this->json($channel,201,[],["groups"=>"forChannel"]);
    }


    #[Route('/remove/{id}',methods: "DELETE")]

    public function removeChannel(Channel $channel, EntityManagerInterface $manager):Response{


        if ($channel->getOwner() != $this->getUser()->getProfile()){
            return $this->json("Vous n'êtes pas le propriétaire de ce channel",200);
        }

        $manager->remove($channel);

        return $this->json("Le channel a bien été supprimé",200);
    }


    #[Route('/edit/{id}',methods: "PUT")]
    public function editChannel(SerializerInterface $serializer, Channel $channel, EntityManagerInterface $manager,Request $request):Response{

        if ($channel->getOwner() == $this->getUser()->getProfile()){
            $channelDeserialized = $serializer->deserialize($request->getContent(),Channel::class,"json",array("object_to_populate"=>$channel));
            $manager->persist($channelDeserialized);
            $manager->flush();
            return $this->json("Vous avez bien modifié le channel d'id ".$channel->getId(),200);
        }

        return $this->json("Vous ne pouvez modifier un channel dont vous n'êtes pas l'auteur",200);
    }


    public function joinChannel(Channel $channel):Response{

        $channelMembers = $channel->getChannelMembers();

        return $this->json("oui");
    }


}
