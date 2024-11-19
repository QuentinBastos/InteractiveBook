<?php

namespace App\Entity\Book;

use App\Repository\Book\RateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RateRepository::class)]
class Rate
{
    // TODO : do rate for a book
}