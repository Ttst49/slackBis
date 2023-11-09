<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/private/message')]
class PrivateMessageController extends AbstractController
{
    #[Route('/send/{id}', name: 'app_private_message')]
    public function index(PrivateConversation $privateConversation): Response
    {
        if ($this->getUser()->getProfile() == $privateConversation->getRelatedToProfileB() or $this->getUser() == $privateConversation->setRelatedToProfileA()){
            dd("coucou");
        }

        return $this->render('private_message/index.html.twig', [
            'controller_name' => 'PrivateMessageController',
        ]);
    }
}
