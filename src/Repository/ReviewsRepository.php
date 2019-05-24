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
     * @param int|null $id
    */

    public function getAllUserReviews(int $userId)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('r.created_at', 'ASC')
        ;
    }
    public function getCountReviews($userId)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_id = :userId')
            ->setParameter('userId', $userId)
            ->select('COUNT(r)')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAverageRating($userId)

    {
      $entityManager = $this->getEntityManager();

      $query = $entityManager->createQuery(
        'SELECT AVG(r.rating)
         FROM APP\Entity\Reviews r
         WHERE r.user_id = :userId'
        )->setParameter('userId', $userId);

        return $query->execute();
    }
}
