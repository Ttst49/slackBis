<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api/')]
class ProfileController extends AbstractController
{
    #[Route('profile/{id}', name: 'app_user',methods: 'GET')]
    public function showProfile(User $user): Response
    {
        return $this->json($user,200,[],["groups"=>"forCreation"]);
    }
}
