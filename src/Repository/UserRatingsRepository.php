<?php

namespace App\Repository;

use App\Entity\UserRatings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserRatings|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRatings|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRatings[]    findAll()
 * @method UserRatings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRatingsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserRatings::class);
    }

    // /**
    //  * @return UserRatings[] Returns an array of UserRatings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserRatings
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
