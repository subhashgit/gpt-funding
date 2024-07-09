<?php

namespace App\Repository\Request;

use App\Entity\Request\ProjectRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectRequest>
 *
 * @method ProjectRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectRequest[]    findAll()
 * @method ProjectRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectRequest::class);
    }

    //    /**
    //     * @return ProjectRequest[] Returns an array of ProjectRequest objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProjectRequest
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
