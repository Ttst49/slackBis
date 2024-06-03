<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\Profile;
use App\Repository\PrivateConversationRepository;
use App\Service\FriendsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/private/conversation')]
class PrivateConversationController extends AbstractController
{
    #[Route('/create/{id}', methods: "POST")]
    public function index(Profile $profile,
                          FriendsService $friendsService,
                          EntityManagerInterface $manager,
                          PrivateConversationRepository $repository
    ): Response
    {
        $friends = $friendsService->getFriends();

        $allFromA = $repository->findBy(
            [
                "relatedToProfileA"=>$this->getUser()->getProfile(),
            ]);
        $allFromB = $repository->findBy(
            [
                "relatedToProfileB"=>$this->getUser()->getProfile()
            ]
        );
        $conversationsOfCurrentUser = $allFromA+$allFromB;
        if ($friends != []){
            foreach ($friends as $item){
                if ($profile->getRelatedTo()->getUsername() == $item->getUsername()){
                    foreach ($conversationsOfCurrentUser as $conversation){
                        if ($conversation
                            ->getRelatedToProfileA()
                            ->getRelatedTo()
                            ->getUsername()==$profile->getRelatedTo()->getUsername()
                            ||
                            $conversation
                            ->getRelatedToProfileB()
                            ->getRelatedTo()
                            ->getUsername()==$profile->getRelatedTo()->getUsername()){
                            return $this->json("Vous avez déjà une conversation privé avec cette personne");
                        }
                    }
                    $privateConversation = new PrivateConversation();
                    $privateConversation->setRelatedToProfileA($this->getUser()->getProfile());
                    $privateConversation->setRelatedToProfileB($profile);
                    $manager->persist($privateConversation);
                    $manager->flush();
                    return $this->json('Nouvelle conversation entre '.$privateConversation->getRelatedToProfileA()->getRelatedTo()->getUsername().' et '.$privateConversation->getRelatedToProfileB()->getRelatedTo()->getUsername(),200);
                }
            }
        }
        return $this->json("Cette personne n'a pas été trouvée",200);
    }


    #[Route('/showMessages/{id}',methods: "GET")]
    public function showPrivateMessagesFromPrivateConversation(PrivateConversation $privateConversation):Response{

        if ($this->getUser() === $privateConversation->getRelatedToProfileB()->getRelatedTo() or $this->getUser() === $privateConversation->getRelatedToProfileA()->getRelatedTo()){
            return $this->json($privateConversation->getPrivateMessages(),200,[],["groups"=>"forPrivateConversation"]);
        }

        return $this->json("Vous ne pouvez pas accéder à cette ressource",403);
    }


    #[Route('/showConversations',methods:"GET")]
    public function showAllConversations(PrivateConversationRepository $repository):Response{

        $actualProfile = $this->getUser()->getProfile();
        $conversationsA = $repository->findBy(["relatedToProfileA"=>$actualProfile]);
        $conversationsB = $repository->findBy(["relatedToProfileB"=>$actualProfile]);
        $allConversations = $conversationsA + $conversationsB;

        return $this->json($allConversations,200,[],["groups"=>"forPrivateConversation"]);
    }

    #[Route('/show/{id}',methods: "GET")]
    public function showGroupConversation(PrivateConversation $privateConversation):Response{

        return $this->json($privateConversation,200,[],["groups"=>"forPrivateConversation"]);
    }
}
