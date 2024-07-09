<?php

namespace App\Repository\Grant;

use App\Entity\Grant\Suggestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Suggestion>
 *
 * @method Suggestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suggestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suggestion[]    findAll()
 * @method Suggestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuggestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suggestion::class);
    }
}
