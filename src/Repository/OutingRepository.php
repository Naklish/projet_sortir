<?php

namespace App\Repository;

use App\Entity\Outing;
use App\Entity\OutingSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Outing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outing[]    findAll()
 * @method Outing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutingRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

    public function findPublishedOutings($userId, OutingSearch $outingSearch): Query
    {
        $query = $this->findPublished($userId);

        if($outingSearch->getCampus()) {
            $query = $query
                ->andWhere('o.campus = :campus')
                ->setParameter('campus', $outingSearch->getCampus());
        }

        if($outingSearch->getMinDate()) {
            if($outingSearch->getMaxDate()) {
                $query = $query->andWhere('o.dateHourStart >= :minDate AND o.dateHourStart <= :maxDate')
                    ->setParameter('minDate', $outingSearch->getMinDate())
                    ->setParameter('maxDate', $outingSearch->getMaxDate());
            }
        }

        if($outingSearch->getSearchBar()) {
            $query = $query->andWhere('o.name LIKE :word')
                ->setParameter("word", '%' . $outingSearch->getSearchBar() . '%');
        }

        if($outingSearch->getCheckOrg()) {
            $query = $query
                ->andWhere('oou = :id')
                ->setParameter('id', $userId);
        }

        if($outingSearch->getCheckFinished()) {
            $query = $query
                ->andWhere('oou != :id AND os = 5')
                ->setParameter('id', $userId);
        }

        if($outingSearch->getCheckRegistered()) {
            $query = $query
                ->andWhere(':id MEMBER OF o.registered_user')
                ->setParameter('id', $userId);
        }

        if($outingSearch->getCheckNotRegistered()) {
            $query = $query
                ->andWhere(':userId NOT MEMBER OF o.registered_user')
                ->setParameter(':userId', $userId);
        }

            return $query->getQuery();
    }

    public function findPublished($userId): QueryBuilder
    {
        $endDate = (new \DateTime("now"))->modify('-1 month');
        return $this->createQueryBuilder('o')
            ->join('o.o_users', 'oou')
            ->addSelect('oou')
            ->leftJoin('o.registered_user', 'oru')
            ->addSelect('oru')
            ->join('o.campus', 'oc')
            ->addSelect('oc')
            ->join('o.state', 'os')
            ->addSelect('os')
            ->andWhere('(os != 1) OR (os = 1 AND oou = :id)')
            ->andWhere('o.dateHourStart > :date')
            ->setParameter('id', $userId)
            ->setParameter('date', $endDate);
    }

    public function findById($idOuting)
    {
        return $this->createQueryBuilder('qb')
            ->andWhere('qb.id = :val')
            ->setParameter('val', $idOuting)
            ->getQuery()
            ->getResult()
            ;
    }
}
