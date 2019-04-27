<?php

namespace App\Repository;

use App\Entity\ProfileComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProfileComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfileComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfileComments[]    findAll()
 * @method ProfileComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileCommentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProfileComments::class);
    }

    // /**
    //  * @return ProfileComments[] Returns an array of ProfileComments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProfileComments
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
