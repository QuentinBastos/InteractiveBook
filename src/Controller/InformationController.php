<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InformationController extends AbstractController
{
    public function __construct(
    )
    {
    }
    #[Route('/about', name: 'app_about')]
    public function about(Request $request): Response
    {
        return $this->render('about.html.twig', [
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig', [
        ]);
    }
}
