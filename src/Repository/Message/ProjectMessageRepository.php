<?php

namespace App\Repository\Message;

use App\Entity\Message\ProjectMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectMessage>
 *
 * @method ProjectMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectMessage[]    findAll()
 * @method ProjectMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectMessage::class);
    }

    //    /**
    //     * @return ProjectMessage[] Returns an array of ProjectMessage objects
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

    //    public function findOneBySomeField($value): ?ProjectMessage
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
