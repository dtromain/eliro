<?php

namespace App\Repository;

use App\Entity\Event;

use App\Entity\State;
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
            ->leftJoin('e.participants', 'participants')
            ->orderBy('e.starttime');

        if ($listFilter['search'] != "") {
            $qb
                ->andWhere('e.name LIKE :search')
                ->setParameter('search', '%' . $listFilter['search'] . '%');
        }
        $archiveDate = new DateTime(sprintf('-%d days', 30));
        if ($listFilter['first_date']<$archiveDate){
            $listFilter['first_date']=$archiveDate;
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


        if ($listFilter['isParticipating']) {
            $orStatements->add(':user MEMBER OF e.participants');
            $qb->setParameter('user',  $qb->expr()->literal($listFilter['user']));
        }
        if ($listFilter['isNotParticipating']) {
            $orStatements->add(':user NOT MEMBER OF e.participants');
            $qb->setParameter('user',  $qb->expr()->literal($listFilter['user']));
        }

        $date = new DateTime();
        if ($listFilter['isPassed']) {
            $orStatements->add(
                $qb->expr()->lt('e.starttime', $qb->expr()->literal($date->format('Y-m-d H:i:s')))
            );
        }
        $qb->andWhere($orStatements);

        $orStatementsBis = $qb->expr()->orX();
        $orStatementsBis->add(
            $qb->expr()->eq('e.planner', $qb->expr()->literal($listFilter['user']))
        );
        /*$orStatementsBis->add(
            $qb->expr()->eq('e.state', State::STATE_OPENED )
        );
        $orStatementsBis->add(
            $qb->expr()->eq('e.state', State::STATE_CLOSED )
        );*/
        $orStatementsBis->add(
            $qb->expr()->eq('e.state',$qb->expr()->literal(State::STATE_PENDING)  )
        );
        /*$orStatementsBis->add(
            $qb->expr()->eq('e.state', State::STATE_FINISHED )
        );*/
        $orStatementsBis->add(
            $qb->expr()->eq('e.state', $qb->expr()->literal(State::STATE_CANCELLED))
        );
        $qb->andWhere($orStatementsBis);

        return $qb->getQuery()->getResult();
    }

    public function findFilter($listFilter)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.campus = :campusId')
            ->setParameter('campusId', $listFilter['campus'])
            ->leftJoin('e.participants', 'participants')
            ->orderBy('e.starttime');

        if ($listFilter['search'] != "") {
            $qb
                ->andWhere('e.name LIKE :search')
                ->setParameter('search', '%' . $listFilter['search'] . '%');
        }
        $archiveDate = new DateTime(sprintf('-%d days', 30));
        if ($listFilter['first_date']<$archiveDate){
            $listFilter['first_date']=$archiveDate;
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


        if ($listFilter['isParticipating']) {
            $orStatements->add(':user MEMBER OF e.participants');
            $qb->setParameter('user',  $qb->expr()->literal($listFilter['user']));
        }/*else{
             $orStatements->add(
                 $qb->expr()->isMemberOf($qb->expr()->literal($listFilter['user']),'e.participants')
             );
         }*/

        $date = new DateTime();
        if ($listFilter['isPassed']) {
            $orStatements->add(
                $qb->expr()->lt('e.starttime', $qb->expr()->literal($date->format('Y-m-d H:i:s')))
            );
        }
        $qb->andWhere($orStatements);
        return $qb->getQuery()->getResult();
    }
}
