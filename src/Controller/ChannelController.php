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
    #[Route('/show/{id}', methods: "GET")]
    public function showChannel(Channel $channel): Response
    {

        return $this->json($channel, 200,[],["groups"=>"forChannel"]);
    }


    #[Route('/showAll', methods: "GET")]
    public function indexChannels(ChannelRepository $repository): Response
    {

        return $this->json($repository->findAll(), 200,[],["groups"=>"forChannel"]);
    }


    #[Route('/create', methods: "POST")]
    public function createChannel(SerializerInterface $serializer, Request $request, EntityManagerInterface $manager): Response
    {

        $channel = $serializer->deserialize($request->getContent(), Channel::class, "json");
        $channel->setOwner($this->getUser()->getProfile());
        $channel->addAdminChannelMember($this->getUser()->getProfile()->getRelatedTo());
        $channel->addChannelMember($this->getUser()->getProfile());
        $manager->persist($channel);
        $manager->flush();

        return $this->json($channel, 201, [], ["groups" => "forChannel"]);
    }


    #[Route('/remove/{id}', methods: "DELETE")]
    public function removeChannel(Channel $channel, EntityManagerInterface $manager): Response
    {


        if ($channel->getOwner() != $this->getUser()->getProfile()) {
            return $this->json("Vous n'êtes pas le propriétaire de ce channel", 200);
        }

        $manager->remove($channel);
        $manager->flush();

        return $this->json("Le channel a bien été supprimé", 200);
    }


    #[Route('/edit/{id}', methods: "PUT")]
    public function editChannel(SerializerInterface $serializer, Channel $channel, EntityManagerInterface $manager, Request $request): Response
    {

        if ($channel->getOwner() == $this->getUser()->getProfile()) {
            $channelDeserialized = $serializer->deserialize($request->getContent(), Channel::class, "json", array("object_to_populate" => $channel));
            $manager->persist($channelDeserialized);
            $manager->flush();
            return $this->json("Vous avez bien modifié le channel d'id " . $channel->getId(), 200);
        }

        return $this->json("Vous ne pouvez modifier un channel dont vous n'êtes pas l'auteur", 200);
    }


    #[Route('/join/{id}', methods: "POST")]
    public function joinChannel(Channel $channel, EntityManagerInterface $manager): Response
    {

        $channelMembers = $channel->getChannelMembers();
        foreach ($channelMembers as $member) {
            if ($member == $this->getUser()->getProfile()) {
                return $this->json("Vous faites visiblement déjà parti de ce channel", 200);

            }
        }
        $channel->addChannelMember($this->getUser()->getProfile());
        $manager->persist($channel);
        $manager->flush();
        return $this->json("Vous avez bien été ajouté au channel " . $channel->getName());
    }


    #[Route('/leave/{id}', methods: "POST")]
    public function leaveChannel(Channel $channel, EntityManagerInterface $manager, UserRepository $userRepository): Response
    {


        $channelMembers = new ArrayCollection();
        $channelAdmins = new ArrayCollection();
        foreach ($channel->getChannelMembers() as $member) {
            $channelMembers->add($member);
        }
        foreach ($channel->getAdminChannelMembers() as $adminChannelMember) {
            $channelAdmins->add($adminChannelMember);
        }
        if ($channel->getOwner() != $this->getUser()->getProfile()) {
            if ($channelAdmins->contains($this->getUser()->getProfile()->getRelatedTo())) {
                $channel->removeAdminChannelMember($this->getUser()->getProfile()->getRelatedTo());
                $channel->removeChannelMember($this->getUser()->getProfile());
                $manager->persist($channel);
                $manager->flush();
                return $this->json("Vous avez bien quitté le channel " . $channel->getName(), 200);
            } elseif ($channelMembers->contains($this->getUser()->getProfile())) {
                $channel->removeChannelMember($this->getUser()->getProfile());
                $manager->persist($channel);
                $manager->flush();
                return $this->json("Vous avez bien quitté le channel " . $channel->getName(), 200);
            }
        } else {
            return $this->json("Vous ne pouvez pas quitter avant d'avoir promu un nouveau propriétaire", 200);
        }


        return $this->json("Vous ne pouvez pas quitter un channel dont vous ne faites pas parti", 200);
    }


    #[Route('/promote/owner/{id}/{userId}', name: "promoteOwner", methods: "POST")]
    #[Route('/promote/admin/{id}/{userId}', name: "promoteAdmin", methods: "POST")]
    #[Route('/demote/admin/{id}/{userId}', name: "demoteAdmin", methods: "POST")]
    public function demoteAdmin(Channel $channel, $userId, UserRepository $repository, EntityManagerInterface $manager, Request $request):Response{

        $concernedUser = $repository->find($userId);
        $collectionOfChannelAdmins = new ArrayCollection();
        foreach ($channel->getAdminChannelMembers() as $channelAdmins){
            $collectionOfChannelAdmins->add($channelAdmins);
        }
        $collectionOfChannelMembers = new ArrayCollection();
        foreach ($channel->getChannelMembers() as $channelMember){
            $collectionOfChannelMembers->add($channelMember);
        }

        if ($channel->getOwner() == $this->getUser()->getProfile()){

            $route = $request->get('_route');
            switch ($route) {
                case $route == "promoteOwner":
                    $channel->setOwner($concernedUser->getProfile());
                    $manager->persist($channel);
                    $manager->flush();
                    return $this->json("L'utilisateur " . $concernedUser->getUsername() . " est désormais propriétaire sur le channel d'id " . $channel->getId(), 200);

                case $route == "promoteAdmin":
                    if ($collectionOfChannelMembers->contains($concernedUser->getProfile())) {
                        $channel->addAdminChannelMember($concernedUser);
                        $manager->persist($channel);
                        $manager->flush();
                        return $this->json("L'utilisateur " . $concernedUser->getUsername() . " est désormais administrateur sur le channel d'id " . $channel->getId(), 200);
                    }

                case $route == 'demoteAdmin':
                    if ($collectionOfChannelAdmins->contains($concernedUser)) {
                        $channel->removeAdminChannelMember($concernedUser);
                        $manager->persist($channel);
                        $manager->flush();
                        return $this->json("L'utilisateur " . $concernedUser->getUsername() . " n'est plus administrateur sur le channel d'id " . $channel->getId(), 200);
                    }
            }


        }
        $response =[
            "status"=>"200",
            "content"=>"Vous ne semblez pas pouvoir faire ça"
        ];


        return $this->json($response,200);
    }



}
