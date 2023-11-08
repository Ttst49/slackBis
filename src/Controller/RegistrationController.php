<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_register_api',methods: "POST")]
    public function registerApi(SerializerInterface $serializer,Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $manager): Response
    {

        $user = $serializer->deserialize($request->getContent(), User::class, "json");

        $parameters = json_decode($request->getContent(), true);

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $parameters["password"]
            )
        );

        $user->setProfile(new Profile());
        $user->getProfile()->setVisibility(true);


        $manager->persist($user);
        $manager->flush();

        return $this->json("L'utilisateur a bien été créé", 200, [], ["groups" => "forCreation"]);

    }
}
