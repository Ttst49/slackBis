<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/group/conversation')]
class GroupConversationController extends AbstractController
{
    #[Route('/create/', name: 'app_group_conversation')]
    public function index(): Response
    {
        return $this->render('group_conversation/index.html.twig', [
            'controller_name' => 'GroupConversationController',
        ]);
    }
}
