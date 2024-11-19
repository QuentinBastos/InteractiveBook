<?php

namespace App\Entity\Book;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\Book\TypeRepository;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    // TODO : do type for a book, it will be parameter for book, need to implement it in a yaml like paramType.yaml
}