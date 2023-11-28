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
    #[Route('/getFriends',methods: "GET")]
    public function getFriends(FriendsService $service):Response{
        $friends = $service->getFriends();

        return $this->json($friends,200,[],["groups"=>"forIndexingProfile"]);
    }

#[Route('/remove/{id}',methods: "DELETE")]
    public function removeFriend(Relation $relation,EntityManagerInterface $manager):Response{

        if ($this->getUser() == $relation->getUserA()->getRelatedTo() or $this->getUser() == $relation->getUserB()->getRelatedTo()){
            $manager->remove($relation);
            $manager->flush();
            return $this->json("L'amitié a été supprimé",200);
        }

        return $this->json("Rien n'a été trouvé à supprimer",200);
    }
}


