<?php

namespace App\Repository;

use App\Entity\Target;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TargetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Target::class);
    }

    public function findByFromPage(int $pageId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.fromPage = :pageId')
            ->setParameter('pageId', $pageId)
            ->getQuery()
            ->getResult();
    }

    public function getTargetsByPage(int $pageId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.fromPage = :pageId')
            ->setParameter('pageId', $pageId)
            ->getQuery()
            ->getResult();
    }
}
