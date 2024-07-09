<?php

namespace App\Repository\Request;

use App\Entity\Request\GrantRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GrantRequest>
 *
 * @method GrantRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrantRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrantRequest[]    findAll()
 * @method GrantRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrantRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrantRequest::class);
    }

    public function paginationQuery(\App\Entity\User $user, $status = null): \Doctrine\ORM\Query
    {
        // return $this->createQueryBuilder('g')
        //     ->where('g.user = :user')
        //     ->setParameter('user', $user)
        //     ->orderBy('g.createdAt', 'DESC')
        //     ->getQuery()
        // ;
        $queryBuilder = $this->createQueryBuilder('g')
        ->where('g.user = :user')
        ->setParameter('user', $user)
        ->orderBy('g.createdAt', 'DESC');

        if ($status !== null) {
            $queryBuilder->andWhere('g.status = :status')
                ->setParameter('status', $status);
        }

        return $queryBuilder->getQuery();
    }
}
