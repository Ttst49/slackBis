<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Repository\ProfileRepository;
use App\Service\FriendsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/group/conversation')]
class GroupConversationController extends AbstractController
{
    #[Route('/create', name: 'app_group_conversation')]
    public function createGroupConversation(FriendsService $service,Request $request, ProfileRepository $repository,EntityManagerInterface $manager): Response{

        $groupConversation = new GroupConversation();
        $friends = $service->getFriends();

        if ($friends){
            $parameters = json_decode($request->getContent(),true);
            foreach ($parameters["members"] as $member){
                $profile = $repository->findOneBy(["id"=>$member]);
                if ($profile){
                    foreach ($friends as $friend){
                        if ($profile->getRelatedTo() == $friend){
                            $groupConversation->addGroupMember($profile);
                        }
                    }
                }
                $groupConversation->setOwner($this->getUser()->getProfile());
                $groupConversation->addAdminMember($this->getUser()->getProfile()->getRelatedTo());
                $manager->persist($groupConversation);
                $manager->flush();
                return $this->json($groupConversation,200,[],["groups"=>"forGroupCreation"]);
            }

        }


        return $this->json('Cette personne ne fait pas partie de vos amis visiblement ',200);
    }
}
