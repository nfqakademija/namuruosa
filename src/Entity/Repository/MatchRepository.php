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

    public function findJobMatches( $userId )
    {

        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->leftJoin('m.callerJobId', 'callerJob')
            ->leftJoin('m.responderJobId', 'responderJob')
            ->andWhere("callerJob.userId = :userId OR responderJob.userId = :userId") // OR responderJob.userId = :userId
            ->setParameters([
                'userId' =>  $userId,
            ])
            ->orderBy('m.createdAt', 'DESC');
        $query = $qb->getQuery();
        $myJobsMatches = $query->execute();

        return $myJobsMatches;
    }

    public function findServicesMatches( $userId )
    {

        $qb = $this->createQueryBuilder('m')
            ->select('m, callerService')
            ->leftJoin('m.callerServiceId', 'callerService')
            ->leftJoin('m.responderServiceId', 'responderService')
            ->andWhere("callerService.userId = :userId OR responderService.userId = :userId")
            ->setParameters([
                'userId' =>  $userId,
            ])
            ->orderBy('m.createdAt', 'DESC');
        $query = $qb->getQuery();
        $myServicesMatches = $query->execute();

        return $myServicesMatches;
    }
}