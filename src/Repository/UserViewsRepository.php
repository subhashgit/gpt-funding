<?php

namespace App\Repository;

use App\Entity\UserViews;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserViews>
 *
 * @method UserViews|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserViews|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserViews[]    findAll()
 * @method UserViews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserViewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserViews::class);
    }

    public function countToday(int $userId): int
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.user = :userId')
            ->andWhere('v.createdAt > :today')
            ->setParameter('userId', $userId)
            ->setParameter('today', new \DateTime('today'))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countThisMonth(int $userId): int
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.user = :userId')
            ->andWhere('v.createdAt > :startOfMonth')
            ->setParameter('userId', $userId)
            ->setParameter('startOfMonth', Carbon::now()->startOfMonth())
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countTodayByEntity(int $userId, int $entityId, string $type): int
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.user = :userId')
            ->andWhere('v.entityId = :entityId')
            ->andWhere('v.type = :type')
            ->andWhere('v.createdAt > :today')
            ->setParameter('userId', $userId)
            ->setParameter('entityId', $entityId)
            ->setParameter('type', $type)
            ->setParameter('today', new \DateTime('today'))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countThisMonthByEntity(int $userId, int $entityId, string $type)
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.user = :userId')
            ->andWhere('v.entityId = :entityId')
            ->andWhere('v.type = :type')
            ->andWhere('v.createdAt > :startOfMonth')
            ->setParameter('userId', $userId)
            ->setParameter('entityId', $entityId)
            ->setParameter('type', $type)
            ->setParameter('startOfMonth', Carbon::now()->startOfMonth())
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
