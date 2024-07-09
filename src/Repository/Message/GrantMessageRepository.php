<?php

namespace App\Repository\Message;

use App\Entity\Message\GrantMessage;
use App\Entity\Request\GrantRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GrantMessage>
 *
 * @method GrantMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrantMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrantMessage[]    findAll()
 * @method GrantMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrantMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrantMessage::class);
    }

    public function getByRequest(GrantRequest $grantRequest): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.request = :grantRequest')
            ->setParameter('grantRequest', $grantRequest)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
