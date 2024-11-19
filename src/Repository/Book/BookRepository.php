<?php

namespace App\Repository\Book;

use App\Entity\Book\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @throws \Exception
     */
    public function get(int $page = 1, int $limit = 10, array $data = []): array
    {
        $qb = $this->createQueryBuilder('b')
            ->orderBy('b.title', 'ASC');

        if (!empty($data['search'])) {
            $qb->andWhere($qb->expr()->like('b.title', ':search'))
                ->setParameter('search', '%' . $data['search'] . '%');
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