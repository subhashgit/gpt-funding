<?php

namespace App\Repository;

use App\Entity\Funding;
use App\Entity\Grant;
use App\Entity\User;
use App\Entity\UserLikes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserLikes>
 *
 * @method UserLikes|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLikes|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLikes[]    findAll()
 * @method UserLikes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLikes::class);
    }

    public function likeFunding(User $user, Funding $funding): void
    {
        $this->likeEntity($user, $funding, 'funding');
    }

    public function likeGrant(User $user, Grant $grant): void
    {
        $this->likeEntity($user, $grant, 'grant');
    }

    public function getLikedIds(User $user, ?array $ids = null, $type = 'funding')
    {
        $qb = $this->createQueryBuilder('ul')
            ->select('f.id')
            ->leftJoin("ul.{$type}", 'f')
            ->where('ul.user = :user')
            ->andWhere('f.id IS NOT NULL')
            ->setParameter('user', $user)
        ;

        if ($ids) {
            $qb->andWhere('f.id IN (:ids)')
                ->setParameter('ids', $ids)
            ;
        }

        return array_column($qb->getQuery()->getResult(), 'id');
    }

    private function likeEntity(User $user, $entity, string $type): void
    {
        if ($this->count(['user' => $user, $type => $entity]) > 0) {
            $userLikes = $this->findOneBy(['user' => $user, $type => $entity]);
            $this->_em->remove($userLikes);
        } else {
            $userLikes = new UserLikes();
            $userLikes->setUser($user);
            if ('funding' === $type) {
                $userLikes->setFunding($entity);
            } elseif ('grant' === $type) {
                $userLikes->setGrant($entity);
            }

            $this->_em->persist($userLikes);
        }

        $this->_em->flush();
    }
}
