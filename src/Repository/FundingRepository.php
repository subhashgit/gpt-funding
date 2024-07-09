<?php

namespace App\Repository;

use App\Entity\Funding;
use App\Enum\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Nette\Utils\DateTime;

/**
 * @extends ServiceEntityRepository<Funding>
 *
 * @method Funding|null find($id, $lockMode = null, $lockVersion = null)
 * @method Funding|null findOneBy(array $criteria, array $orderBy = null)
 * @method Funding[]    findAll()
 * @method Funding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FundingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Funding::class);
    }

    public function save(Funding $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Funding $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(?string $query, ?array $filters = null, string $order = 'published_date', string $direction = 'DESC'): \Doctrine\ORM\Query
    {
        $qb = $this->createQueryBuilder('f');

        $qb->andWhere('f.status = :status')
            ->setParameter('status', Status::OPEN->value)
        ;

        if ($query) {
            $qb
                ->andWhere(
                    $qb->expr()->orX(
                        ...array_map(
                            static fn ($word) => $qb->expr()->like('lower(f.title)', "lower('%{$word}%')"),
                            explode(' ', $query)
                        )
                    )
                )
            ;
        }

        if ($filters) {
            foreach ($filters as $key => $filter) {
                switch ($key) {
                    case 'value_low':
                        if ($filter) {
                            $qb->andWhere("f.value_low >= :{$key}")
                                ->setParameter($key, $filter)
                            ;
                        }

                        break;

                    case 'value_high':
                        if ($filter) {
                            $qb->andWhere("f.value_high <= :{$key}")
                                ->setParameter($key, $filter)
                            ;
                        }

                        break;

                    case 'region':
                        if ($filter) {
                            $qb
                                ->leftJoin('f.fundingRegions', 'fr')
                                ->andWhere('fr.region in (:region)')
                                ->setParameter('region', $filter)
                            ;
                        }

                        break;

                    case 'sector':
                        if ($filter) {
                            $qb
                                ->leftJoin('f.cpv', 'fc')
                            ;

                            $qb
                                ->andWhere(
                                    $qb->expr()->orX(
                                        ...array_map(
                                            static function ($k) use ($qb) {
                                                $str_split = str_split($k, 2);

                                                return $qb->expr()->like('fc.code', "'{$str_split[0]}%'");
                                            },
                                            $filter
                                        )
                                    )
                                )
                            ;
                        }

                        break;

                    case 'publication':
                        if ($filter['publication_start_date']) {
                            $qb->andWhere('f.published_date >= :publication_start_date')
                                ->setParameter('publication_start_date', DateTime::from($filter['publication_start_date']))
                            ;
                        }

                        if ($filter['publication_end_date']) {
                            $qb->andWhere('f.published_date <= :publication_end_date')
                                ->setParameter('publication_end_date', DateTime::from($filter['publication_end_date']))
                            ;
                        }

                        break;

                    case 'closing':
                        if ($filter['closing_start_date']) {
                            $qb->andWhere('f.closing_date >= :closing_start_date')
                                ->setParameter('closing_start_date', $filter['closing_start_date'])
                            ;
                        }

                        if ($filter['closing_end_date']) {
                            $qb->andWhere('f.closing_date <= :closing_end_date')
                                ->setParameter('closing_end_date', $filter['closing_end_date'])
                            ;
                        }

                        break;

                    default:
                        if ($filter) {
                            $qb->andWhere("f.{$key} in (:{$key})")
                                ->setParameter($key, $filter)
                            ;
                        }

                        break;
                }
            }
        }

        $qb->groupBy('f.id');
        $qb->orderBy("f.{$order}", $direction);

        return $qb->getQuery();
    }

    public function getFilters(string $name)
    {
        $qb = $this->createQueryBuilder('f');

        //        $qb
        //            ->andWhere('f.status = :status')
        //            ->andWhere('f.closing_date is not null')
        //            ->setParameter(
        //                'status',
        //                Status::OPEN->value,
        //            )
        //        ;

        if ('region' === $name) {
            $qb
                ->join('f.fundingRegions', 'fr')
                ->select('fr.region')
                ->andWhere('fr.show_in_filter = true')
                ->groupBy('fr.region')
            ;
        } elseif ('sector' === $name) {
            $qb
                ->leftJoin('f.cpv', 'fc')
                ->select('fc.description as sector', 'fc.code as code')
                ->andWhere('fc.code is not null')
                ->groupBy('fc.description')
                ->addGroupBy('fc.code')
                ->orderBy('fc.description', 'ASC')
            ;

            return array_column($qb->getQuery()->getResult(), $name, 'code');
        } else {
            $qb
                ->select("f.{$name}")
                ->groupBy("f.{$name}")
                ->orderBy("f.{$name}", 'ASC')
                ->where("f.{$name} is not null")
            ;
        }

        return array_column($qb->getQuery()->getResult(), $name);
    }

    public function updateDeadlines()
    {
        $qb = $this->createQueryBuilder('f');

        $qb->update()
            ->set('f.status', ':setStatus')
            ->where('f.closing_date <= :date')
            ->andWhere('f.status = :status')
            ->setParameters([
                'date' => new \DateTime(),
                'status' => Status::OPEN->value,
                'setStatus' => Status::CLOSED->value,
            ])
        ;

        return $qb->getQuery()->execute();
    }

    public function getEnded(): array
    {
        $qb = $this->createQueryBuilder('f');

        $qb
            ->where('f.closing_date <= :closing_date')
            ->andWhere('f.status = :status')
            ->setParameters([
                'closing_date' => new \DateTime(),
                'status' => Status::ACTIVE->value,
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    public function findForEmail(array $ids)
    {
        return $this->createQueryBuilder('g')
            ->select('g.id, g.title, g.description, g.slug')
            ->where('g.id in (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getByDate(\DateTime $param)
    {
        return $this->createQueryBuilder('g')
            ->where('g.closing_date >= :param')
            ->orWhere('g.closing_date is null')
            ->setParameter('param', $param)
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function hasParent(?string $ocid = null, ?\DateTimeImmutable $publishedDate = null): bool
    {
        if (!$ocid) {
            return false;
        }

        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->where('g.ocid = :ocid')
            ->andWhere('g.published_date > :published_date')
            ->setParameters([
                'ocid' => $ocid,
                'published_date' => $publishedDate,
            ])
            ->getQuery()
            ->getSingleScalarResult() > 0
        ;
    }
}
