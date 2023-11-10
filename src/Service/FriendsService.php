<?php

namespace App\Service;

use App\Repository\RelationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FriendsService extends AbstractController
{
    private $repository;

    public function __construct(RelationRepository $repository){
        $this->repository = $repository;
    }

    public function getFriends():array{

        $friends = [];

        foreach ($this->repository->findAll() as $item){
            if ($this->getUser()->getProfile()->getId() == $item->getUserA()->getId()){
                $friends[] = $item->getUserB()->getRelatedTo();
            }elseif($this->getUser()->getProfile()->getId() == $item->getUserB()->getId()){
                $friends[] = $item->getUserA()->getRelatedTo();
            }
        }
        return $friends;
    }
}