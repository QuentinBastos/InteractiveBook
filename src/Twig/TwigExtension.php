<?php

namespace App\Twig;

use App\Repository\PageRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    private PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_last_page_id_plus_one', ($this->getLastPageIdPlusOne(...))),
        ];
    }

    public function getLastPageIdPlusOne(): int
    {
        $lastPage = $this->pageRepository->findOneBy([], ['id' => 'DESC']);
        return $lastPage ? $lastPage->getId() + 1 : 1;
    }
}