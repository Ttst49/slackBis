<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Relation;
use App\Entity\User;
use App\Service\FriendsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

#[Route('api/relations')]
class RelationsController extends AbstractController
{
    #[Route('/getFriends',methods: "GET")]
    public function getFriends(FriendsService $service):Response{
        $friends = $service->getFriends();

        return $this->json($friends,200,[],["groups"=>"forIndexingProfile"]);
    }


    #[Route('/getRelations/{id}',methods: "GET")]
    public function getRelationsFromUser(User $user):Response{

        $relations = $user->getProfile()->getRelations();

        return $this->json($relations,200,[],["groups"=>"forRelationIndexing"]);
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


