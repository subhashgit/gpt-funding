<?php

namespace App\Repository;

use App\Entity\UserTariff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserTariff>
 *
 * @method UserTariff|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTariff|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTariff[]    findAll()
 * @method UserTariff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTariffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTariff::class);
    }

    //    /**
    //     * @return UserTariff[] Returns an array of UserTariff objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserTariff
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
