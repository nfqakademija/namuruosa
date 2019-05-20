<?php

namespace App\Repository;

use App\Entity\Reviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reviews[]    findAll()
 * @method Reviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reviews::class);
    }

     /*
     * @return Reviews[] Returns an array of Reviews objects
    */

    public function findAllUserReviews(int $id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_id = :id')
            ->setParameter('id', $id)
            ->orderBy('r.created_at', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
     /*
     * @param int|null $id
    */

    public function getAllUserReviews(int $id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_id = :id')
            ->setParameter('id', $id)
            ->orderBy('r.created_at', 'ASC')
        ;
    }
    public function getCountReviews($id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_id = :id')
            ->setParameter('id', $id)
            ->select('COUNT(r)')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAverageRating($id)

    {
      $entityManager = $this->getEntityManager();

      $query = $entityManager->createQuery(
        'SELECT AVG(r.rating)
         FROM APP\Entity\Reviews r
         WHERE r.user_id = :id'
        )->setParameter('id', $id);

        return $query->execute();
    }


    /*
    public function findOneBySomeField($value): ?Reviews
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
