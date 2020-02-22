<?php

namespace App\Repository;

use App\Entity\Event;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

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

    public function findNotHappendByPage($page, $itemPerPage)
    {
        /** @var QueryBuilder $qb */
        $qb =  $this->createQueryBuilder('e');

        return $qb
            ->where('e.starttime > :now')
            ->setParameter('now', new DateTime())
            ->setMaxResults($itemPerPage)
            ->setFirstResult(($page - 1) * $itemPerPage)
            ->orderBy('e.starttime')
            ->getQuery()
            ->getResult();
    }
    public function findNotHappend()
    {
        /** @var QueryBuilder $qb */
        $qb =  $this->createQueryBuilder('e');

        return $qb
            ->where('e.starttime > :now')
            ->setParameter('now', new DateTime())
            ->orderBy('e.starttime')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
