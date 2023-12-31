<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/')]
class ProfileController extends AbstractController
{
    #[Route('profile/show/{id}', methods: 'GET')]
    public function showProfile(User $user): Response
    {
        return $this->json($user,200,[],["groups"=>"forIndexingProfile"]);
    }

    #[Route('profile/showAll', methods: "GET")]
    public function showAllUser(UserRepository $repository):Response{

        $users=[];
        foreach ($repository->findAll() as $user){
            if ($user->getProfile()->isVisibility()){
                $users[]=$user;
            }
        }
        return $this->json($users,200,[],["groups"=>"forIndexingProfile"]);
    }

    #[Route('profile/getActual',methods: "GET")]
    public function getActualUserProfile():Response{
        $user = $this->getUser();

        return $this->json($user,200,[],["groups"=>"forIndexingProfile"]);
    }
}
