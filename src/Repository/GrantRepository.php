<?php

namespace App\Repository;

use App\Entity\Grant;
use App\Enum\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grant>
 *
 * @method Grant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grant[]    findAll()
 * @method Grant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grant::class);
    }

    public function save(Grant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Grant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(?string $query, ?array $filters, string $order = 'published_date', string $direction = 'DESC')
    {
        $qb = $this->createQueryBuilder('g');

        if ($query) {
            $qb
                ->andWhere(
                    $qb->expr()->orX(
                        ...array_map(
                            static fn ($word) => $qb->expr()->like('lower(g.title)', "lower('%{$word}%')"),
                            explode(' ', $query)
                        )
                    )
                )
            ;
        }

        if ($filters) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'location':
                        if ($value) {
                            $qb->join('g.locations', 'gl');

                            $qb
                                ->andWhere(
                                    $qb->expr()->orX(
                                        ...array_map(
                                            static fn ($k) => $qb->expr()->eq('gl.location', "'{$k}'"),
                                            $value
                                        )
                                    )
                                )
                            ;
                        }

                        break;

                    case 'category':
                        if ($value) {
                            $qb
                                ->join('g.categories', 'gc')
                            ;

                            $qb
                                ->andWhere(
                                    $qb->expr()->orX(
                                        ...array_map(
                                            static fn ($k) => $qb->expr()->eq('gc.category', "'{$k}'"),
                                            $value
                                        )
                                    )
                                )
                            ;
                        }

                        break;

                    case 'open_to':
                        if ($value) {
                            $qb
                                ->join('g.openTo', 'go')
                            ;

                            $qb
                                ->andWhere(
                                    $qb->expr()->orX(
                                        ...array_map(
                                            static fn ($k) => $qb->expr()->eq('go.open_to', "'{$k}'"),
                                            $value
                                        )
                                    )
                                )
                            ;
                        }

                        break;

                    default:
                }
            }
        }

        $qb->orderBy("g.{$order}", $direction);

        return $qb->getQuery();
    }

    public function getFilters(string $name): array
    {
        $qb = $this->createQueryBuilder('g');

        if ('location' === $name) {
            $qb
                ->join('g.locations', 'gl')
                ->select('gl.location, gl.id')
                ->orderBy('gl.location', 'ASC')
                ->groupBy('gl.location')
                ->andWhere('gl.show_in_filter = true')
                ->addGroupBy('gl.id')
            ;
        } elseif ('open_to' === $name) {
            $qb
                ->join('g.openTo', 'go')
                ->select('go.open_to, go.id')
                ->orderBy('go.open_to', 'ASC')
                ->groupBy('go.open_to')
                ->andWhere('go.show_in_filter = true')
                ->addGroupBy('go.id')
            ;
        } elseif ('category') {
            $qb
                ->join('g.categories', 'gc')
                ->select('gc.category, gc.id')
                ->groupBy('gc.category')
                ->andWhere('gc.show_in_filter = true')
                ->orderBy('gc.category', 'ASC')
                ->addGroupBy('gc.id')
            ;
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

    public function getEnded(): array
    {
        $qb = $this->createQueryBuilder('f');

        $qb
            ->where('f.closing_date < :closing_date')
            ->andWhere('f.status = :status')
            ->setParameters([
                'closing_date' => new \DateTime(),
                'status' => Status::OPEN->value,
            ])
        ;

        return $qb->getQuery()->getResult();
    }
}
