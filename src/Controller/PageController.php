<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Page;
use App\Form\FileUploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/page')]
class PageController extends AbstractController
{


    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/upload', name: 'page_upload')]
    public function upload(Request $request, EntityManagerInterface $entityManager): Response
    {
        $page = new Page();
        $form = $this->createForm(FileUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            if ($file) {
                $fileName = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('uploads_directory'), $fileName);
                $page->setFilePath($fileName);
                $entityManager->persist($page);
                $entityManager->flush();

                return $this->redirectToRoute('api_request');
            }
        }

        return $this->render('page/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{bookId}/create/{pageId}', name: 'page_create')]
    public function create(int $bookId, int $pageId, int $parentId, Request $request): Response
    {
        $book = $this->em->getRepository(Book::class)->find($bookId);
        $page = $this->em->getRepository(Page::class)->find($pageId);

        if (!$book || !$page) {
            throw $this->createNotFoundException('The book or page does not exist');
        }

        if ($bookId !== Page::FIRST_PAGE) {
            if ($parentId) {
                $parent = $this->em->getRepository(Page::class)->find($parentId);
                $page->setParent($parent);
            }
            $page = new Page();
            $page->setBook($book);
            $page->setNumber($pageId);
            $page->setContent('test');
            $page->setFilePath('test2');
            $this->em->persist($page);
            $this->em->flush();
        }

        return $this->render('page/create.html.twig', [
            'page' => $page,
            'book' => $book,
        ]);
    }
}