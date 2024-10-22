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

    #[Route('/create', name: 'page_create')]
    public function create(int $pageId): Response
    {
        $page = $this->em->getRepository(Page::class)->find($pageId);
        $book = $page->getBook();
        return $this->render('page/create.html.twig', [
            'page' => $page,
            'book' => $book,
        ]);
    }
}