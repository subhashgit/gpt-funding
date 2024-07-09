<?php

namespace App\Repository\Request;

use App\Entity\Request\FundingRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FundingRequest>
 *
 * @method FundingRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FundingRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FundingRequest[]    findAll()
 * @method FundingRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FundingRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FundingRequest::class);
    }

    //    /**
    //     * @return Funding[] Returns an array of Funding objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Funding
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
