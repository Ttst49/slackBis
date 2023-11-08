<?php

namespace App\Controller;

use App\Entity\Relation;
use App\Repository\RelationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/friend')]
class FriendController extends AbstractController
{
    #[Route('/getFriends')]
    public function getFriends(RelationRepository $repository):Response{

        $friends = [];

        foreach ($repository->findAll() as $item){
            if ($this->getUser()->getProfile()->getId() == $item->getUserA()->getId()){
                $friends[] = $item->getUserB()->getRelatedTo();
            }elseif($this->getUser()->getProfile()->getId() == $item->getUserB()->getId()){
                $friends[] = $item->getUserA()->getRelatedTo();
            }
        }

        return $this->json($friends,200,[],["groups"=>"forIndexingProfile"]);
    }

    #[Route('/remove/{id}')]
    public function removeFriend(Relation $relation,EntityManagerInterface $manager):Response{

        if ($this->getUser() == $relation->getUserA()->getRelatedTo() or $this->getUser() == $relation->getUserB()->getRelatedTo()){
            $manager->remove($relation);
            $manager->flush();
            return $this->json("L'amitié a été supprimé",200);
        }

        return $this->json("Rien n'a été trouvé à supprimer",200);
    }
}


# entité privateconversation et private message
# pour les conv de groupe = new entité qui a un tableau de user au lieu de seulement 2 user comme dans les conv privées
# système admin avec droits
# ajouter des reponses à chaque message
# entité privatemessageresponse groupmessageresponse chanelmessageresponse
# entité image et controlleur image associer les images à une entité message
# limiter le nombres de messages qu'on voit avec le bundle de pagination ou à la main dans le repo