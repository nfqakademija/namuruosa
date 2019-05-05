<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.4
 * Time: 21.01
 */

namespace App\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository
{

    public function findByUserId($userId)
    {

        return $this->getEntityManager()->createQuery(
            "
            SELECT s 
                FROM App\Entity\Job s 
            WHERE s.userId= :id 
            ORDER BY s.updatedAt DESC
            "
        )
            ->setParameter('id', $userId)
            ->getResult();

    }
}