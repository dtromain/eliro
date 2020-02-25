<?php

namespace App\Repository;

use App\Entity\Event;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use DateTime;
use function Sodium\add;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findByPageFilter($page, $itemPerPage, $listFilter)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.campus = :campusId')
            ->setParameter('campusId', $listFilter['campus'])
            ->setMaxResults($itemPerPage)
            ->setFirstResult(($page - 1) * $itemPerPage)
            ->orderBy('e.starttime');
        if ($listFilter['search'] != "") {
            $qb
                ->andWhere('e.name LIKE :search')
                ->setParameter('search', '%' . $listFilter['search'] . '%');
        }
        if ($listFilter['second_date'] != "") {
            $qb
                ->andWhere('e.starttime > :firstdate')
                ->setParameter('firstdate', $listFilter['first_date'])
                ->andWhere('e.starttime < :seconddate')
                ->setParameter('seconddate', $listFilter['second_date']);
        } else {
            $qb
                ->andWhere('e.starttime > :firstdate')
                ->setParameter('firstdate', $listFilter['first_date']);
        }

        $orStatements = $qb->expr()->orX();
        if ($listFilter['isPlanner']) {
            $orStatements->add(
                $qb->expr()->eq('e.planner', $qb->expr()->literal($listFilter['user']))
            );
        }
        $date = new DateTime();
        if ($listFilter['isPassed']) {
            $orStatements->add(
                $qb->expr()->lt('e.starttime', $qb->expr()->literal($date->format('Y-m-d H:i:s')))
            );
        }
        $qb->andWhere($orStatements);
        return $qb->getQuery()->getResult();
    }

    public function findFilter($index_form)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('e');

        return $qb
            ->where('e.starttime > :now')
            ->setParameter('now', new DateTime())
            ->orderBy('e.starttime')
            ->getQuery()
            ->getResult();
    }
}
