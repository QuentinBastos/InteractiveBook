<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookCreateType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


#[Route('/book')]
class BookController extends AbstractController
{

    public function __construct(
        private readonly TranslatorInterface    $translator,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/create', name: 'book_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(BookCreateType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = new Book($this->translator);
            $book->setType($form->get('type')->getData());
            $book->setTitle($form->get('title')->getData());
            $this->em->persist($book);
            $this->em->flush();
        }
        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}