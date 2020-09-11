<?php

namespace App\Repository;

use App\Entity\Planification;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Planification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planification[]    findAll()
 * @method Planification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planification::class);
    }

     /**
     * @return int Returns nbr of array of Planification objects
     */

    public function findNbrToDay()
    {
        $date = new DateTime('00:00:00');
        $query = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.date = :val')
            ->setParameter('val', $date)
            ;

        $query = $query->getQuery();
        return $query->getSingleScalarResult();
    }

    /**
     * @return int Returns nbr of array of Planification objects
     */

    public function findNbrSupToDay()
    {
        $date = new DateTime('00:00:00');
        $query = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.date > :val')
            ->setParameter('val', $date)
            ;

        $query = $query->getQuery();
        return $query->getSingleScalarResult();
    }
    /**
     * @return Planification[] Returns an array of Planification objects
    */
    
    public function findLastTen()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

}
