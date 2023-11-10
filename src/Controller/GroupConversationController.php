<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/group/conversation')]
class GroupConversationController extends AbstractController
{
    #[Route('/create', name: 'app_group_conversation')]
    public function createGroupConversation(Request $request, ProfileRepository $repository): Response{

        $groupConversation = new GroupConversation();

        $parameters = json_decode($request->getContent(),true);
        foreach ($parameters["members"] as $member){
            $profile = $repository->findOneBy(["id"=>$member]);
            if ($profile){
                $groupConversation->addGroupMember($profile);
            }
            $groupConversation->setOwner($this->getUser()->getProfile());

        }


        return $this->json($parameters["members"],200);
    }
}
