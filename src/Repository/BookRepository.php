<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findByTitle(string $search): array
    {
        $qb = $this->createQueryBuilder('b');

        if (!empty($search)) {
            $qb->andWhere('b.title LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}