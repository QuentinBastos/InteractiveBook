<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
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
            $user = $this->getUser();
            if ($user instanceof User) {
                $book = new Book($this->translator);
                $book->setType($form->get('type')->getData());
                $book->setTitle($form->get('title')->getData());
                $book->setUser($this->getUser());
                $this->em->persist($book);
                $this->em->flush();
                return $this->redirectToRoute('page_create');
            } else {
                $error = $this->translator->trans('form.error.user_not_logged');
            }
        }
        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
            'error' => $error ?? false,
        ]);
    }
}