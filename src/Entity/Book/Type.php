<?php

namespace App\Entity\Book;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\Book\TypeRepository;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{

}