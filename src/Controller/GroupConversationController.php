<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\Profile;
use App\Repository\ProfileRepository;
use App\Service\FriendsService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
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



    #[Route('/promote/admin/{id}/{userId}')]
    public function promoteAdmin(GroupConversation $groupConversation, $userId, ProfileRepository $repository,EntityManagerInterface $manager):Response{

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


    #[Route('/promote/owner/{id}/{userId}')]
    public function promoteOwner(GroupConversation $groupConversation, $userId, ProfileRepository $repository, EntityManagerInterface $manager):Response{

        if ($this->getUser()->getProfile() == $groupConversation->getOwner()){
            $user = $repository->findOneBy(["id"=>$userId]);
            foreach ($groupConversation->getAdminMembers() as $adminMember){
                if ($user->getRelatedTo() == $adminMember){
                    $groupConversation->setOwner($user);
                    $manager->persist($groupConversation);
                    $manager->flush();

                    return $this->json("Le nouveau propriétaire du groupe est ".$groupConversation->getOwner()->getRelatedTo()->getUsername(),200);
                }else{
                    return $this->json("Vous ne pouvez que promouvoir chef de groupe qu'un membre administrateur",200);
                }
            }
        }
        return $this->json("Vous devez être le propriétaire du groupe pour faire ça",200);

    }


    #[Route('/leave/{id}')]
    public function leaveGroupConversation(GroupConversation $groupConversation,EntityManagerInterface $manager):Response{

        $adminsCounter = $groupConversation->getAdminMembers()->count();

        foreach ($groupConversation->getAdminMembers() as $adminMember){
            foreach ($groupConversation->getGroupMembers() as $groupMember){

            if($this->getUser() == $adminMember and $adminsCounter == 1){
                return $this->json("tu dois promouvoir quelqu'un administrateur pour pouvoir quitter ce groupe",200);
            }elseif ($this->getUser() == $adminMember and $adminsCounter > 1){
                $groupConversation->removeAdminMember($this->getUser()->getProfile()->getRelatedTo());
                $groupConversation->removeGroupMember($this->getUser()->getProfile());
                $manager->persist($groupConversation);
                $manager->flush();
                return $this->json("vous avez bien quitté le groupe d'id: ".$groupConversation->getId(),200);
            }elseif ($this->getUser() != $adminMember and $this->getUser() == $groupMember){
                $groupConversation->removeGroupMember($this->getUser()->getProfile());
                $manager->persist($groupConversation);
                $manager->flush();
                return $this->json("Vous avez bien quitté le groupe d'id ".$groupConversation->getId(),200);
            }
            }
        }


        return $this->json("une erreur est survenue",200);
    }

    #[Route('/add/{id}')]
    public function addNewMember(GroupConversation $groupConversation, Request $request, ProfileRepository $repository, EntityManagerInterface $manager, FriendsService $service):Response{

        $friends = $service->getFriends();

        foreach ($groupConversation->getGroupMembers() as $groupMember){
            if($this->getUser() == $groupMember->getRelatedTo()){
                $parameters = json_decode($request->getContent(),true);
                foreach ($parameters["members"] as $member) {
                    $profile = $repository->findOneBy(["id" => $member]);
                    foreach ($friends as $friend){
                        if ($profile->getRelatedTo() == $friend){
                            foreach ($groupConversation->getGroupMembers() as $groupMember){
                                if ($groupMember == $profile){
                                    return $this->json("L'utilisateur ".$groupMember->getRelatedTo()->getUsername()." est déjà membre du gorupe",200);
                                }else{
                                    $groupConversation->addGroupMember($profile);
                                    $manager->persist($groupConversation);
                                    $manager->flush();
                                    return $this->json("Le membre ".$profile->getRelatedTo()->getUsername()." a bien été ajouté au groupe",200);
                                }
                            }
                        }else{
                            return $this->json("Cette personne ne fait visiblement pas parti de vos amis",200);
                        }
                    }
                }
            }
        }

        return $this->json("Une erreur est survenue, n'y aurait-il pas une erreur dans la requete?",200);
    }





}
