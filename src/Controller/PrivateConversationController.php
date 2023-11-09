<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\Profile;
use App\Repository\RelationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/private/conversation')]
class PrivateConversationController extends AbstractController
{
    #[Route('/create/{id}', name: 'app_private_conversation')]
    public function index(Profile $profile, FriendController $controller, RelationRepository $relation,EntityManagerInterface $manager): Response
    {
        $friends = [];

        foreach ($relation->findAll() as $item){
            if ($this->getUser()->getProfile()->getId() == $item->getUserA()->getId()){
                $friends[] = $item->getUserB()->getRelatedTo();
            }elseif($this->getUser()->getProfile()->getId() == $item->getUserB()->getId()){
                $friends[] = $item->getUserA()->getRelatedTo();
            }
        }

        if ($friends != []){
            foreach ($friends as $item){
                if ($profile->getRelatedTo()->getUsername() == $item->getUsername()){
                    $privateConversation = new PrivateConversation();
                    $privateConversation->setRelatedToProfileA($this->getUser()->getProfile());
                    $privateConversation->setRelatedToProfileB($profile);
                    $manager->persist($privateConversation);
                    $manager->flush();
                    return $this->json('Nouvelle conversation entre '.$privateConversation->getRelatedToProfileA()->getRelatedTo()->getUsername().' et '.$privateCOnversation->getRelatedToProfileB()->getRelatedTo()->getUsername(),200);
                }
            }
        }

        return $this->json("Cette personne n'a pas été trouvée",200);
    }
}
