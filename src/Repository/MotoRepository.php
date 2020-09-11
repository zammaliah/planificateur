<?php

namespace App\Repository;

use App\Entity\Moto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Moto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Moto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Moto[]    findAll()
 * @method Moto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Moto::class);
    }
}
