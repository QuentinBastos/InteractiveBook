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


    #[Route('/{bookId}/create/{pageId}/{parentId?}', name: 'page_create')]
    public function create(int $bookId, int $pageId, Request $request, ?int $parentId = null): Response
    {
        $isFirstPage = ($pageId === 1);
        $book = $this->em->getRepository(Book::class)->find($bookId);
        $form = $this->createForm(PageCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$book) {
                throw $this->createNotFoundException('The book does not exist');
            }

            $page = new Page();
            $page->setBook($book);
            $page->setNumber($this->pageManager->getLastPageByBook($book) + 1);
            $page->setContent($form->get('apiMessage')->getData()['message']);
            $page->setFilePath($form->get('fileUpload')->getData()['file']);

            if ($parentId) {
                $parent = $this->em->getRepository(Page::class)->find($parentId);
                if ($parent) {
                    $page->setParent($parent);
                }
            }

            $this->em->persist($page);
            $this->em->flush();

            return $this->render('page/added.html.twig', [
                'page' => $page,
                'book' => $book,
            ]);
        }

        return $this->render('page/create.html.twig', [
            'previous_page' => $page ?? null,
            'page' => $page ?? null,
            'first_page' => $isFirstPage,
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }
}