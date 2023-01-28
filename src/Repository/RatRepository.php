<?php

namespace App\Repository;

use App\Entity\Rat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rat[]    findAll()
 * @method Rat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rat::class);
    }

}
