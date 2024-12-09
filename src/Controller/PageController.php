<?php

namespace App\Controller;

use App\Entity\Book\Book;
use App\Entity\Page;
use App\Entity\Target;
use App\Form\Page\PageCreateType;
use App\Manager\PageManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/page')]
class PageController extends AbstractController
{


    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PageManager            $pageManager
    )
    {
    }

    #[Route('/{bookId}/add/{pageId}/{parentId?}', name: 'page_add')]
    public function add(int $bookId, int $pageId, SluggerInterface $slugger, string $uploadDirectoryPage, Request $request, ?int $parentId = null): Response
    {
        $isFirstPage = ($pageId === 1);
        $book = $this->em->getRepository(Book::class)->find($bookId);
        $page = $this->em->getRepository(Page::class)->find($pageId);

        if (!$page) {
            $message = 'add';
            $page = new Page();
            $page->setBook($book);
            if ($parentId) {
                $parent = $this->em->getRepository(Page::class)->find($parentId);
                if ($parent) {
                    $page->setParent($parent);
                }
            }
        } else {
            $message = 'edit';
        }

        $form = $this->createForm(PageCreateType::class, $page, [
            'book' => $book,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$book) {
                throw $this->createNotFoundException('The book does not exist');
            }

            $page->setNumber($this->pageManager->getLastPageByBook($book) + 1);
            $page->setContent($form->get('content')->getData());
            $fileUpload = $form->get('filePath')->getData();
            if ($fileUpload) {
                $originalFilename = pathinfo($fileUpload->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fileUpload->guessExtension();
                $fileUpload->move($uploadDirectoryPage, $newFilename);
                $page->setFilePath($newFilename);
            }

            $toTargets = $form->get('toTargets')->getData();
            foreach ($toTargets as $target) {
                if ($target instanceof Target) {
                    $target->setFromPage($page);
                    $this->em->persist($target);
                }
            }


            $this->em->persist($page);
            $this->em->flush();

            return $this->render('page/after_add.html.twig', [
                'first_page' => $isFirstPage,
                'page' => $page,
                'book' => $book,
                'message' => $message,
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

    #[Route('/book/{bookId}/page/{pageId?}/show', name: 'page_show')]
    public function show(int $bookId, ?int $pageId = null): Response
    {
        $book = $this->em->getRepository(Book::class)->find($bookId);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        if ($pageId === null) {
            $firstPage = $this->em->getRepository(Page::class)->findOneBy([
                'book' => $book,
                'parent' => null,
            ]);

            if (!$firstPage) {
                throw $this->createNotFoundException('No first page found for this book.');
            }

            return $this->redirectToRoute('page_show', [
                'bookId' => $bookId,
                'pageId' => $firstPage->getId(),
            ]);
        }

        $page = $this->em->getRepository(Page::class)->find($pageId);

        if (!$page || $page->getBook() !== $book) {
            throw $this->createNotFoundException('Page not found or does not belong to this book.');
        }

        $toTargets = $page->getToTargets();

        return $this->render('page/show.html.twig', [
            'book' => $book,
            'page' => $page,
            'toTargets' => $toTargets,
        ]);
    }


    #[Route('/{bookId}/page/update/{pageId}', name: 'page_update')]
    public function update(
        int              $bookId,
        int              $pageId,
        SluggerInterface $slugger,
        string           $uploadDirectoryPage,
        Request          $request
    ): Response
    {
        $book = $this->em->getRepository(Book::class)->find($bookId);
        $page = $this->em->getRepository(Page::class)->find($pageId);

        if (!$book || !$page) {
            throw $this->createNotFoundException('The book or page does not exist');
        }

        $form = $this->createForm(PageCreateType::class, $page, [
            'book' => $book,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->setContent($form->get('content')->getData());

            $fileUpload = $form->get('filePath')->getData();
            if ($fileUpload) {
                $originalFilename = pathinfo($fileUpload->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fileUpload->guessExtension();
                $fileUpload->move($uploadDirectoryPage, $newFilename);
                $page->setFilePath($newFilename);
            }

            $toTargets = $form->get('toTargets')->getData();
            if ($toTargets instanceof ArrayCollection) {
                foreach ($toTargets as $target) {
                    if ($target instanceof Target) {
                        $target->setFromPage($page);
                        $this->em->persist($target);
                    }
                }
            }

            $this->em->persist($page);
            $this->em->flush();

            $this->addFlash('success', 'Page updated successfully.');

            return $this->redirectToRoute('book_update', ['id' => $bookId]);
        }

        return $this->render('page/update.html.twig', [
            'page' => $page,
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

}