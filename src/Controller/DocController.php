<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('')]
class DocController extends AbstractController
{
    #[Route('', name: 'app_doc')]
    public function index(): Response
    {
        return $this->render("doc/index.html.twig",[
        ]);
    }
}
