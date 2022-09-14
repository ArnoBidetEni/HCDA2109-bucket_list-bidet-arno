<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function home(): Response
    {
        $username = $this->getUser()->getLogin();
        return $this->render('main/home.html.twig', [
            'username' => $username
        ]);
    }

    #[Route('/legal-stuff', name: 'legal_stuff')]
    public function legalStuff(): Response
    {
        return $this->render('main/legalStuff.html.twig', []);
    }

    #[Route('/about-us', name: 'about_us')]
    public function aboutUs(): Response
    {
        return $this->render('main/about-us.html.twig', []);
    }
}
