<?php

namespace App\Repository\Notification;

use App\Entity\Notification\UserGrantNotification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserGrantNotification>
 *
 * @method UserGrantNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGrantNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGrantNotification[]    findAll()
 * @method UserGrantNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGrantNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGrantNotification::class);
    }

    public function getNotNotifiedUsers()
    {
        return $this->createQueryBuilder('n')
            ->select('u.id')
            ->leftJoin('n.user', 'u')
            ->andWhere('n.notified = false')
            ->groupBy('u.id')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getGrantIdsByUser(int $userId): array
    {
        return array_column($this->createQueryBuilder('n')
            ->select('n.grant_id')
            ->andWhere('n.user_id = :userId')
            ->andWhere('n.notified = false')
            ->setParameter('userId', $userId)
            ->groupBy('n.grant_id')
//            ->orderBy('n.priority', 'DESC')
            ->getQuery()
            ->getResult(), 'grant_id');
    }

    public function updateNotified(int $userId)
    {
        return $this->createQueryBuilder('n')
            ->update()
            ->set('n.notified', 'true')
            ->andWhere('n.user_id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute()
        ;
    }

    public function paginationQuery(User $user): Query
    {
        return $this->createQueryBuilder('n')
            ->where('n.user = :user')
            ->andWhere("n.createdAt >= '2023-11-09'")
            ->setParameter('user', $user)
            ->orderBy('n.priority', 'DESC')
            ->addOrderBy('n.createdAt', 'DESC')
            ->addOrderBy('n.createdAt', 'DESC')
            ->getQuery()
        ;
    }
}
