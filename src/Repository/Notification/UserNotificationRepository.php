<?php

namespace App\Repository\Notification;

use App\Entity\Notification\UserNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserNotification>
 *
 * @method UserNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserNotification[]    findAll()
 * @method UserNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserNotification::class);
    }

    public function save(UserNotification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserNotification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNotNotified()
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.notified = false')
            ->groupBy('n.grant_id')
            ->getQuery()
            ->getResult()
        ;
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

    public function getByUser(int $userId)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user_id = :userId')
            ->andWhere('n.notified = false')
            ->setParameter('userId', $userId)
            ->groupBy('n.grant_id')
            ->orderBy('n.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
