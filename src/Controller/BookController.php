<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\BookCreateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/create', name: 'book_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(BookCreateType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchQuery = $form->get('type')->getData();
        }
        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}