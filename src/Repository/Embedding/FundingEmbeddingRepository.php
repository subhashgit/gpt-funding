<?php

namespace App\Repository\Embedding;

use App\Entity\Embedding\FundingEmbedding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FundingEmbedding>
 *
 * @method FundingEmbedding|null find($id, $lockMode = null, $lockVersion = null)
 * @method FundingEmbedding|null findOneBy(array $criteria, array $orderBy = null)
 * @method FundingEmbedding[]    findAll()
 * @method FundingEmbedding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FundingEmbeddingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FundingEmbedding::class);
    }

    /**
     * Finds grants that are similar to a given vector.
     *
     * @param string $vector the vector to compare against
     * @param int    $limit  The maximum number of results to return. Defaults to 40.
     *
     * @return array an array of grants that are similar to the given vector
     *
     * @throws Exception
     */
    public function vectorFind(string $vector, int $limit = 40): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = <<<EOF
select "funding".id, title, description, vector <-> '{$vector}' AS distance, slug
from embedding
         left join "funding" on "funding".id = embedding.entity_id
where type = 'grant' and (vector <-> '{$vector}' < 0.75 and vector <-> '{$vector}' != 1) 
--   and ("funding".end_date > now() or "funding".end_date is null)
order by distance
limit {$limit}
EOF;

        return $conn->prepare($sql)->executeQuery()->fetchAllAssociative();
    }

    public function similar(string $vector, $locations = null, $categories = null, $openTo = null, int $limit = 40): array
    {
        $locationsQueryWhere = '';
        $locationsQueryJoin = '';
        if ($locations) {
            $locations = implode(',', $locations);
            $locationsQueryJoin = <<<'EOF'
left join grant_grant_location on grant_grant_location.grant_id = "grant".id
EOF;
            $locationsQueryWhere = " and grant_grant_location.grant_location_id in ({$locations})";
        }

        $categoriesQueryWhere = '';
        $categoriesQueryJoin = '';
        if ($categories) {
            $categories = implode(',', $categories);
            $categoriesQueryJoin = <<<'EOF'
left join grant_grant_category on grant_grant_category.grant_id = "grant".id
EOF;
            $categoriesQueryWhere = " and grant_grant_category.grant_category_id in ({$categories})";
        }

        $openToQueryWhere = '';
        $openToQueryJoin = '';
        if ($openTo) {
            $openTo = implode(',', $openTo);
            $openToQueryJoin = <<<'EOF'
left join grant_grant_open_to on grant_grant_open_to.grant_id = "grant".id
EOF;
            $openToQueryWhere = " and grant_grant_open_to.grant_open_to_id in ({$openTo})";
        }

        $conn = $this->getEntityManager()->getConnection();
        $sql = <<<EOF
select "grant".id, title, description, vector <-> '{$vector}' AS distance, slug
from embedding
         left join "grant" on "grant".id = embedding.entity_id
        {$locationsQueryJoin}
        {$categoriesQueryJoin}
        {$openToQueryJoin}
where type = 'grant' and (vector <-> '{$vector}' < 0.75 and vector <-> '{$vector}' != 1) 
{$locationsQueryWhere} 
{$categoriesQueryWhere}
{$openToQueryWhere}
and ("grant".closing_date >= now() or "grant".closing_date is null)
group by "grant".id,embedding.vector
order by distance
limit {$limit}
EOF;

        return $conn->prepare($sql)->executeQuery()->fetchAllAssociative();
    }
}
