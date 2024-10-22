<?php

namespace App\Controller;

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
    #[Route('/upload', name: 'app_page_upload')]
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
}