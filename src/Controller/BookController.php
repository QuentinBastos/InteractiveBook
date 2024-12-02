<?php

namespace App\Controller;


use App\Entity\Book\Book;
use App\Entity\Page;
use App\Entity\User;
use App\Form\Book\BookCreateType;
use App\Form\Book\FilterType;
use App\Manager\PageManager;
use App\Utils\Constants;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
        private readonly PageManager            $pageManager,
    )
    {
    }

    #[Route('/add', name: 'book_add')]
    public function add(Request $request): Response
    {
        $form = $this->createForm(BookCreateType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if ($user instanceof User) {
                $book = new Book();
                $now = new \DateTime();
                $types = $form->get('types')->getData();
                foreach ($types as $type) {
                    $book->addType($type);
                }
                $book->setTitle($form->get('title')->getData());
                $book->setUser($this->getUser());
                $book->setCreatedAt($now);
                $book->setUpdatedAt($now);
                $this->em->persist($book);
                $this->em->flush();
                return $this->redirectToRoute('page_add', [
                    'bookId' => $book->getId(),
                    'pageId' => Page::FIRST_PAGE
                ]);
            } else {
                $error = $this->translator->trans('form.error.user_not_logged');
            }
        }
        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
            'error' => $error ?? false,
        ]);
    }

    #[Route('/update/{id}', name: 'book_update')]
    public function update(Request $request): Response
    {
        $book = $this->em->getRepository(Book::class)->find($request->get('id'));
        return $this->render('book/update.html.twig', [
            'book' => $book,
            'first_page' => $this->pageManager->getFirstPageByBook($book),
        ]);
    }


    #[Route('/show', name: 'book_show_all')]
    public function showAll(Request $request, PaginatorInterface $paginator): Response
    {
        $data = [];
        $page = $request->query->getInt('page', 1);

        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data['search'] = $form->get('search')->getData();
            $data['author'] = $form->get('author')->getData();
            $data['rate'] = $form->get('rate')->getData();
            $data['types'] = $form->get('types')->getData();
        }

        $limit = Constants::PAGE_LIMIT;
        $result = $this->em->getRepository(Book::class)->get($page, $limit, $data);
        $totalItems = $result['totalItems'];
        $books = $result['items'];

        $totalPages = ceil($totalItems / $limit);
        $nextPage = $page < $totalPages ? $page + 1 : null;
        $prevPage = $page > 1 ? $page - 1 : null;

        return $this->render('book/show_all.html.twig', [
            'form' => $form->createView(),
            'books' => $books,
            'currentPage' => $page,
            'nextPage' => $nextPage,
            'prevPage' => $prevPage,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/show/{id}', name: 'book_show')]
    public function show(Request $request): Response
    {
        return $this->render('book/show.html.twig');
    }
}