<?php

namespace App\Repository\Embedding;

use App\Entity\Embedding\Embedding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Embedding>
 *
 * @method Embedding|null find($id, $lockMode = null, $lockVersion = null)
 * @method Embedding|null findOneBy(array $criteria, array $orderBy = null)
 * @method Embedding[]    findAll()
 * @method Embedding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmbeddingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Embedding::class);
    }

    public function vectorFind(array $vectorArray)
    {
        $vector = json_encode($vectorArray);

        return $this->createQueryBuilder('e')
            ->addselect("e.vector <-> '{$vector}' as distance")
            ->setMaxResults(10)
            ->orderBy('distance', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
