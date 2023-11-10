<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\Profile;
use App\Repository\ProfileRepository;
use App\Service\FriendsService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/group/conversation')]
class GroupConversationController extends AbstractController
{
    #[Route('/show/{id}')]
    public function showGroupConversation(GroupConversation $groupConversation):Response{

        return $this->json($groupConversation,200,[],["groups"=>"forGroupIndexing"]);
    }




    #[Route('/create', methods: "POST")]
    public function createGroupConversation(FriendsService $service,Request $request, ProfileRepository $repository,EntityManagerInterface $manager): Response{

        $groupConversation = new GroupConversation();
        $friends = $service->getFriends();

        if ($friends != []){
            $parameters = json_decode($request->getContent(),true);
            foreach ($parameters["members"] as $member) {
                $profile = $repository->findOneBy(["id" => $member]);
                if ($profile) {
                    foreach ($friends as $friend) {
                        if ($profile->getRelatedTo() == $friend) {
                            $groupConversation->addGroupMember($profile);
                        }
                    }
                }
            }

                if ($groupConversation->getGroupMembers() == new ArrayCollection()){
                    return $this->json("Vous devez associer des amis pour créer un groupe",200);
                }
                $groupConversation->setOwner($this->getUser()->getProfile());
                $groupConversation->addAdminMember($this->getUser()->getProfile()->getRelatedTo());
                $groupConversation->addGroupMember($this->getUser()->getProfile());
                $manager->persist($groupConversation);
                $manager->flush();
                return $this->json($groupConversation,200,[],["groups"=>"forGroupCreation"]);


        }


        return $this->json('Cette personne ne fait pas partie de vos amis visiblement ',200);
    }



    #[Route('/promote/{id}/{userId}')]
    public function promoteAdmin(GroupConversation $groupConversation, $userId, ProfileRepository $repository, Request $request,EntityManagerInterface $manager):Response{

        foreach ($groupConversation->getAdminMembers() as $adminMember){
            if ($this->getUser() == $adminMember){
                $user = $repository->findOneBy(["id"=>$userId]);
                foreach ($groupConversation->getGroupMembers() as $groupMember){
                    if ($user == $groupMember){
                        $groupConversation->addAdminMember($user->getRelatedTo());
                        $manager->persist($groupConversation);
                        $manager->flush();
                        return $this->json($user->getRelatedTo()->getUsername()." a bien été promu Admin",200);
                    }
                }
            }
        }


        return $this->json("Vous ne pouvez pas faire ça",200);
    }
}
