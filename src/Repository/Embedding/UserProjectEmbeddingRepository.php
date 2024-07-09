<?php

namespace App\Repository\Embedding;

use App\Entity\Embedding\UserProjectEmbedding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProjectEmbedding>
 *
 * @method UserProjectEmbedding|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProjectEmbedding|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProjectEmbedding[]    findAll()
 * @method UserProjectEmbedding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProjectEmbeddingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProjectEmbedding::class);
    }

    //    /**
    //     * @return UserProjectEmbedding[] Returns an array of UserProjectEmbedding objects
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

    //    public function findOneBySomeField($value): ?UserProjectEmbedding
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
