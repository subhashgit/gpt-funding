<?php

namespace App\Repository;

use App\Entity\ScoresRanks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScoresRanks>
 *
 * @method ScoresRanks|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScoresRanks|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScoresRanks[]    findAll()
 * @method ScoresRanks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoresRanksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScoresRanks::class);
    }

    //    /**
    //     * @return AllIoD2019ScoresRanksDecilesPopulationDenominators[] Returns an array of AllIoD2019ScoresRanksDecilesPopulationDenominators objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AllIoD2019ScoresRanksDecilesPopulationDenominators
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
