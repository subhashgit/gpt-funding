<?php

namespace App\Repository;

use App\Entity\CPVCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CPVCode>
 *
 * @method CPVCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method CPVCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method CPVCode[]    findAll()
 * @method CPVCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CPVCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CPVCode::class);
    }

    public function save(CPVCode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CPVCode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return CPVCode[] Returns an array of CPVCode objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CPVCode
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findMainCategory(array $sectors)
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('c.description', 'c.code', 'c.id')
        ;

        foreach ($sectors as $key => $sector) {
            $qb->orWhere("c.code like :sector{$key}")
                ->setParameter("sector{$key}", $sector.'-%')
            ;
        }

        return array_column($qb
            ->getQuery()
            ->getResult(), 'description', 'code');
    }

    public function findMainCategoryIds(array $sectors)
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('c.id')
        ;

        foreach ($sectors as $key => $sector) {
            $qb->orWhere("c.code like :sector{$key}")
                ->setParameter("sector{$key}", $sector.'-%')
            ;
        }

        return array_column($qb
            ->getQuery()
            ->getResult(), 'id');
    }
}
