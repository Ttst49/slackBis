<?php

namespace App\Controller;

use App\Entity\Relation;
use App\Service\FriendsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/relations')]
class RelationsController extends AbstractController
{
    #[Route('/getFriends')]
    public function getFriends(FriendsService $service):Response{
        $friends = $service->getFriends();

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
# bien changer les noms pour qu'ils ressemlbent aux entités