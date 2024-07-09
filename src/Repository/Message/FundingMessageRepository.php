<?php

namespace App\Repository\Message;

use App\Entity\Message\FundingMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FundingMessage>
 *
 * @method FundingMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method FundingMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method FundingMessage[]    findAll()
 * @method FundingMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FundingMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FundingMessage::class);
    }
}
