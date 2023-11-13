<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupMessageResponseController extends AbstractController
{
    #[Route('/group/message/response', name: 'app_group_message_response')]
    public function index(): Response
    {
        return $this->render('group_message_response/index.html.twig', [
            'controller_name' => 'GroupMessageResponseController',
        ]);
    }
}
