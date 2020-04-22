<?php

namespace App\Repository;

use App\Entity\Blogger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blogger|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blogger|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blogger[]    findAll()
 * @method Blogger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BloggerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blogger::class);
    }
}
