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
         WHERE ((m.callerId = :userId  AND m.callerServiceId IS NOT NULL)
         OR (m.responderId = :userId AND m.responderServiceId IS NOT NULL))
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
         WHERE ((m.callerId = :userId  AND m.callerJobId IS NOT NULL)
         OR (m.responderId = :userId AND m.responderJobId IS NOT NULL))
         AND m.payedAt IS NOT NULL'
    )->setParameter('userId', $userId);

    return $query->execute();
    }

    public function getAllMatches()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
                SELECT m.id, m.caller_id, m.caller_service_id, m.responder_id,
                       m.responder_job_id, m.created_at, m.accepted_at, m.rejected_at, m.cancelled_at,
                       c.username as caller_username, r.username as responder_username,
                       s.title as service_title,
                       j.title as job_title
                FROM matches m
                    LEFT JOIN fos_user c 
                        ON m.caller_id = c.id
                    LEFT join fos_user r
                        ON m.responder_id = r.id
                    LEFT JOIN service s 
                        ON IF(m.caller_service_id IS NULL, m.responder_service_id, m.caller_service_id) = s.id
                    LEFT JOIN job j 
                        ON IF(m.caller_job_id IS NULL, m.responder_job_id, m.caller_job_id) = j.id
                        ";

        $query = $conn->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function findJobMatchesByJobId($jobId)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.callerJobId', 'callerJob')
            ->leftJoin('m.responderJobId', 'responderJob')
            ->andWhere("callerJob.id = :jobId OR responderJob.id = :jobId")
            ->setParameters([
                'jobId' => $jobId,
            ])
            ->orderBy('m.createdAt', 'DESC');
        $query = $qb->getQuery();
        $myJobMatches = $query->execute();

        return $myJobMatches;
    }

    public function findServiceMatchesByServiceId($serviceId)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.callerServiceId', 'callerService')
            ->leftJoin('m.responderServiceId', 'responderService')
            ->andWhere("callerService.id = :serviceId OR responderService.id = :serviceId")
            ->setParameters([
                'serviceId' => $serviceId,
            ])
            ->orderBy('m.createdAt', 'DESC');
        $query = $qb->getQuery();
        $myServiceMatches = $query->execute();

        return $myServiceMatches;
    }
}
