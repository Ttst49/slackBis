<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Repository\ChannelRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
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


    #[Route('/join/{id}',methods: "POST")]
    public function joinChannel(Channel $channel,EntityManagerInterface $manager):Response{

        $channelMembers = $channel->getChannelMembers();
        foreach ($channelMembers as $member){
            if ($member == $this->getUser()->getProfile()){
                return $this->json("Vous faites visiblement déjà parti de ce channel",200);

            }
        }
        $channel->addChannelMember($this->getUser()->getProfile());
        $manager->persist($channel);
        $manager->flush();
        return $this->json("Vous avez bien été ajouté au channel ".$channel->getName());
    }


    #[Route('/leave/{id}',methods: "POST")]
    public function leaveChannel(Channel $channel,EntityManagerInterface $manager, UserRepository $userRepository):Response{


        $channelMembers = new ArrayCollection();
        $channelAdmins = new ArrayCollection();
        foreach ($channel->getChannelMembers() as $member){
            $channelMembers->add($member);
        }
        foreach ($channel->getAdminChannelMembers() as $adminChannelMember){
            $channelAdmins->add($adminChannelMember);
        }
        if ($channel->getOwner() != $this->getUser()->getProfile()){
            if ($channelAdmins->contains($this->getUser()->getProfile()->getRelatedTo())){
                $channel->removeAdminChannelMember($this->getUser()->getProfile()->getRelatedTo());
                $channel->removeChannelMember($this->getUser()->getProfile());
                $manager->persist($channel);
                $manager->flush();
                return $this->json("Vous avez bien quitté le channel ".$channel->getName(),200);
            }elseif ($channelMembers->contains($this->getUser()->getProfile())){
                $channel->removeChannelMember($this->getUser()->getProfile());
                $manager->persist($channel);
                $manager->flush();
                return $this->json("Vous avez bien quitté le channel ".$channel->getName(),200);
            }
        }else{
            return $this->json("Vous ne pouvez pas quitter avant d'avoir promu un nouveau propriétaire",200);
        }


        return $this->json("Vous ne pouvez pas quitter un channel dont vous ne faites pas parti",200);
    }


    #[Route('/promote/owner/{id}/{userId}')]
    public function promoteToOwner(Channel $channel, $userId, UserRepository $repository,EntityManagerInterface $manager):Response{

        $newOwner = $repository->find($userId);
        $currentUser = $repository->find($this->getUser()->getProfile()->getRelatedTo());
        if ($newOwner == $currentUser){
            return $this->json("Vous ne pouvez pas faire cette action sur vous même",200);
        }
        if ($currentUser == $channel->getOwner()->getRelatedTo()){
            foreach ($channel->getChannelMembers() as $member){
                if ($member === $newOwner->getProfile()){
                    $channel->setOwner($newOwner->getProfile());
                    $manager->persist($channel);
                    $manager->flush();
                    return $this->json($newOwner->getUsername()." a été promu propriétaire du channel",200);
                }else{
                    return $this->json("Cet utilisateur ne fait pas parti de ce channel",200);
                }
            }
        }

        return $this->json("Vous ne pouvez pas faire ça",200);
    }


}
