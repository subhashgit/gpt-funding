<?php

namespace App\Repository;

use App\Entity\UserFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFilter>
 *
 * @method UserFilter|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFilter|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFilter[]    findAll()
 * @method UserFilter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFilterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFilter::class);
    }

    public function save(UserFilter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserFilter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return UserFilter[] Returns an array of UserFilter objects
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

    //    public function findOneBySomeField($value): ?UserFilter
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function getUsersByFilterTypes(array $openToIds, array $categoryIds, array $locationIds)
    {
        $qb = $this->createQueryBuilder('uf');
        //        $qb->select(['uf.type', 'uf.user', 'uf.type_id']);

        $qb->orWhere("uf.type = 'openTo' and uf.type_id in (:openToIds)")
            ->setParameter('openToIds', $openToIds)
        ;

        $qb->orWhere("uf.type = 'category' and uf.type_id in (:categoryId)")
            ->setParameter('categoryId', $categoryIds)
        ;

        $qb->orWhere("uf.type = 'location' and uf.type_id in (:locationId)")
            ->setParameter('locationId', $locationIds)
        ;

        //        $qb->groupBy('uf.type')
        //            ->addGroupBy('uf.user')

        return $qb->getQuery()->getResult();
    }
}
