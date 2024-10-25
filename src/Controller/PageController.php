<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Page;
use App\Form\FileUploadType;
use App\Form\PageCreateType;
use App\Manager\PageManager;
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
        private readonly PageManager            $pageManager
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


    #[Route('/{bookId}/add/{pageId}/{parentId?}', name: 'page_add')]
    public function add(int $bookId, int $pageId, Request $request, ?int $parentId = null): Response
    {
        $isFirstPage = ($pageId === 1);
        $book = $this->em->getRepository(Book::class)->find($bookId);
        $page = $this->em->getRepository(Page::class)->find($pageId);

        if (!$page) {
            $page = new Page();
            $page->setBook($book);
            if ($parentId) {
                $parent = $this->em->getRepository(Page::class)->find($parentId);
                if ($parent) {
                    $page->setParent($parent);
                }
            }
        }

        $form = $this->createForm(PageCreateType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$book) {
                throw $this->createNotFoundException('The book does not exist');
            }

            $page->setNumber($this->pageManager->getLastPageByBook($book) + 1);
            $page->setContent($form->get('content')->getData());
            $page->setFilePath($form->get('filePath')->getData());

            $this->em->persist($page);
            $this->em->flush();

            return $this->render('page/after_add.html.twig', [
                'page' => $page,
                'book' => $book,
            ]);
        }

        return $this->render('page/add.html.twig', [
            'previous_page' => $page ?? null,
            'page' => $page ?? null,
            'first_page' => $isFirstPage,
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }
}