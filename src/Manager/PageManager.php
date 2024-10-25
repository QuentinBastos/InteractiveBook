<?php

namespace App\Manager;

use App\Entity\Book;
use App\Entity\Page;

class PageManager
{
    public function getLastPageByBook(Book $book): int
    {
        $pages = $book->getPages();
        if ($pages->isEmpty()) {
            return Page::NO_PAGE;
        }
        $lastPage = null;
        foreach ($pages as $page) {
            if ($lastPage === null || $page->getId() > $lastPage->getId()) {
                $lastPage = $page;
            }
        }
        return $lastPage->getNumber();
    }

    public function getFirstPageByBook(Book $book): ?Page
    {
        $pages = $book->getPages();
        if ($pages->isEmpty()) {
            return null;
        }
        $firstPage = null;
        foreach ($pages as $page) {
            if ($firstPage === null || $page->getId() < $firstPage->getId()) {
                $firstPage = $page;
            }
        }
        return $firstPage;
    }
}
