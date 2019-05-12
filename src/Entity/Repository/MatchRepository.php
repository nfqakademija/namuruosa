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

//        $myCats = $service->getCategory()->toArray();
//
//        $myLat = $service->getLat();
//        $myLon = $service->getLon();
//        $myId = $service->getUserId()->getId();
//            $myServiceId = $service->getId();
//            $myId2 = $service->getUserId()->getId();

        $qb = $this->createQueryBuilder('m')
            ->select('m')
//            ->addSelect('( (j.lat - :myLat) * (j.lat - :myLat) + (j.lon - :myLon) * (j.lon - :myLon)) / 100
//                 AS distance')// Distance just for sorting, not for real values
//                ->andWhere('s.userId <> :myId')
//            ->addSelect('j.lat AS belekas')
            ->leftJoin('m.callerJobId', 'job')
            ->andWhere("job.userId = :userId")
//            ->andWhere('j.lat BETWEEN :minLat AND :maxLat ')
//            ->andWhere('j.lon BETWEEN :minLon AND :maxLon ')
//                ->andWhere( 'distance <> 500'  )

            ->setParameters([
                'userId' =>  $userId,
//                'myLat' => $myLat,
//                'myLon' => $myLon,
//                'maxLat' => $myLat + 30,
//                'maxLon' => $myLon + 30,
//                'minLat' => $myLat - 30,
//                'minLon' => $myLon - 30,
//                    'myId' => $myId,
            ])
            ->orderBy('m.createdAt', 'DESC');

        $query = $qb->getQuery();
        $myJobsMatches = $query->execute();

        return $myJobsMatches;

    }

}