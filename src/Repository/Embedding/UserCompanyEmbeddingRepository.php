<?php

namespace App\Repository\Embedding;

use App\Entity\Embedding\UserCompanyEmbedding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserCompanyEmbedding>
 *
 * @method UserCompanyEmbedding|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCompanyEmbedding|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCompanyEmbedding[]    findAll()
 * @method UserCompanyEmbedding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCompanyEmbeddingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCompanyEmbedding::class);
    }

    public function average(int $companyId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = <<<EOF
select avg(vector) AS vector
from embedding
where entity_id = {$companyId} and type = 'user_company'
EOF;

        return $conn->prepare($sql)->executeQuery()->fetchOne();
    }
}
