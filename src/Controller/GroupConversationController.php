<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupConversationController extends AbstractController
{
    #[Route('/group/conversation', name: 'app_group_conversation')]
    public function index(): Response
    {
        return $this->render('group_conversation/index.html.twig', [
            'controller_name' => 'GroupConversationController',
        ]);
    }
}
