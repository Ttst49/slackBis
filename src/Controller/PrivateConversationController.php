<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\Profile;
use App\Repository\RelationRepository;
use App\Service\FriendsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/private/conversation')]
class PrivateConversationController extends AbstractController
{
    #[Route('/create/{id}', name: 'app_private_conversation')]
    public function index(Profile $profile, FriendsService $friendsService, RelationRepository $relation,EntityManagerInterface $manager): Response
    {
        $friends = $friendsService->getFriends();

        if ($friends != []){
            foreach ($friends as $item){
                if ($profile->getRelatedTo()->getUsername() == $item->getUsername()){
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


    #[Route('/showMessages/{id}')]
    public function showPrivateMessagesFromPrivateConversation(PrivateConversation $privateConversation):Response{

        if ($this->getUser() === $privateConversation->getRelatedToProfileB()->getRelatedTo() or $this->getUser() === $privateConversation->getRelatedToProfileA()->getRelatedTo()){
            return $this->json($privateConversation->getPrivateMessages(),200,[],["groups"=>"forPrivateConversation"]);
        }

        return $this->json("Vous ne pouvez pas accéder à cette ressource",403);
    }
}
