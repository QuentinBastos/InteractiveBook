<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @throws \Exception
     */
    public function get(int $page = 1, int $limit = 10, string $search = ''): array
    {
        $qb = $this->createQueryBuilder('b')
            ->orderBy('b.title', 'ASC');

        if (!empty($search)) {
            $qb->andWhere($qb->expr()->like('b.title', ':search'))
                ->setParameter('search', '%' . $search . '%');
        }

        $query = $qb->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return [
            'totalItems' => $totalItems,
            'items' => $qb->getQuery()->getResult(),
        ];
    }

    public function findByTitle(string $search): array
    {
        $qb = $this->createQueryBuilder('b');

        if (!empty($search)) {
            $qb->andWhere($qb->expr()->like('b.title', ':search'))
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}