<?php

namespace App\Repository\Notification;

use App\Entity\Notification\UserFundingNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFundingNotification>
 *
 * @method UserFundingNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFundingNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFundingNotification[]    findAll()
 * @method UserFundingNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFundingNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFundingNotification::class);
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

    public function getFundingIdsByUser(int $userId): array
    {
        return array_column($this->createQueryBuilder('n')
            ->select('n.funding_id')
            ->andWhere('n.user_id = :userId')
            ->andWhere('n.notified = false')
            ->setParameter('userId', $userId)
            ->groupBy('n.funding_id')
            ->getQuery()
            ->getResult(), 'funding_id');
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
}
