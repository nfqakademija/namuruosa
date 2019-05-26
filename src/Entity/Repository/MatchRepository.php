<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.27
 * Time: 12.21
 */

namespace App\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class MatchRepository extends EntityRepository
{

    public function findJobsMatches($userId)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.callerJobId', 'callerJob')
            ->leftJoin('m.responderJobId', 'responderJob')
            ->andWhere("callerJob.userId = :userId OR responderJob.userId = :userId")
            ->setParameters([
                'userId' => $userId,
            ])
            ->orderBy('m.createdAt', 'DESC');
        $query = $qb->getQuery();
        $myJobsMatches = $query->execute();

        return $myJobsMatches;
    }

    public function findServicesMatches($userId)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m, callerService')
            ->leftJoin('m.callerServiceId', 'callerService')
            ->leftJoin('m.responderServiceId', 'responderService')
            ->andWhere("callerService.userId = :userId OR responderService.userId = :userId")
            ->setParameters([
                'userId' => $userId,
            ])
            ->orderBy('m.createdAt', 'DESC');
        $query = $qb->getQuery();
        $myServicesMatches = $query->execute();

        return $myServicesMatches;
    }

    public function countUserServices($userId): array
    {
      $entityManager = $this->getEntityManager();

      $query = $entityManager->createQuery(
        'SELECT COUNT(m)
         FROM App\Entity\Match m
         WHERE (m.callerId = :userId  AND m.callerServiceId IS NOT NULL)
         OR (m.responderId = :userId AND m.responderServiceId IS NOT NULL)
         AND m.payedAt IS NOT NULL'
    )->setParameter('userId', $userId);

    return $query->execute();
    }

    public function countUserJobs($userId): array
    {
      $entityManager = $this->getEntityManager();

      $query = $entityManager->createQuery(
        'SELECT COUNT(m)
         FROM App\Entity\Match m
         WHERE (m.callerId = :userId  AND m.callerJobId IS NOT NULL)
         OR (m.responderId = :userId AND m.responderJobId IS NOT NULL)
         AND m.payedAt IS NOT NULL'
    )->setParameter('userId', $userId);

    return $query->execute();
    }
}
