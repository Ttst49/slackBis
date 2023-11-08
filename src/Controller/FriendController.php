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
                $friends[] = $item->getUserB()->getRelatedTo()->getUsername();
            }elseif($this->getUser()->getProfile()->getId() == $item->getUserB()->getId()){
                $friends[] = $item->getUserA()->getRelatedTo()->getUsername();
            }
        }

        return $this->json($friends,200);
    }

    #[Route('/remove/{id}')]
    public function removeFriend(Relation $relation,EntityManagerInterface $manager):Response{

        if ($this->getUser() == $relation->getUserA() or $this->getUser() == $relation->getUserB()){
            $manager->remove($relation);
            $manager->flush();
            return $this->json("L'amitié a été supprimé",200);
        }

        return $this->json("Rien n'a été trouvé à supprimer",200);
    }
}
