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
        $qb = $this->createQueryBuilder('b');
        $expr = $qb->expr();

        $qb->orderBy('b.title', 'ASC');

        if (!empty($data['search'])) {
            $qb->andWhere($expr->like('b.title', ':search'))
                ->setParameter('search', '%' . $data['search'] . '%');
        }

        if (!empty($data['author'])) {
            $qb->andWhere($expr->eq('b.user', ':author'))
                ->setParameter('author', $data['author']);
        }

        if (!empty($data['rate'])) {
            $qb->andWhere($expr->eq('b.rate', ':rate'))
                ->setParameter('rate', $data['rate']);
        }

        if (!empty($data['types'])) {
            $qb->join('b.types', 't')
                ->andWhere($expr->in('t.id', ':types'))
                ->setParameter('types', $data['types']);
        }

        // TODO : repair the filter by count of pages
        if (isset($data['fromPage']) || isset($data['toPage'])) {
            $qb->join('b.pages', 'p');
        }
        if (isset($data['fromPage'])) {
            if ($data['fromPage'] === 0) {
                $qb->andWhere($expr->isNull('b.pages'));
            } else {
                $qb->andWhere($expr->gte('p.number', ':fromPage'))
                    ->setParameter('fromPage', $data['fromPage']);
            }
        }
        if (isset($data['toPage'])) {
            $qb->andWhere($expr->lte('p.number', ':toPage'))
                ->setParameter('toPage', $data['toPage']);
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
        $expr = $qb->expr();

        if (!empty($search)) {
            $qb->andWhere($expr->like('b.title', ':search'))
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}