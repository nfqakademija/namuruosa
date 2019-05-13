<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 08.19
 */

namespace App\Entity\Repository;


use App\Entity\Job;
use App\Entity\Service;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ServiceRepository extends EntityRepository
{
    public function findServicesByUserId($id)
    {
        return $this->getEntityManager()->createQuery(
            " SELECT s 
            FROM App\Entity\Service s 
            WHERE s.userId= :id 
            ORDER BY s.updatedAt DESC "
        )
            ->setParameter('id', $id)
            ->getResult();
    }


    public function findMatches(Job $job)
    {
        $myCats = $job->getCategory()->toArray();
        $myLat = $job->getLat();
        $myLon = $job->getLon();
        $myId = $job->getUserId()->getId();

        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->addSelect('( (s.lat - :myLat) * (s.lat - :myLat) + (s.lon - :myLon) * (s.lon - :myLon)) / 100 AS distance')// Distance just for sorting ONLY
            ->andWhere('s.userId <> :myId')
            ->addSelect('s.lat AS belekas')
            ->leftJoin('s.category', 'category')
            ->andWhere("category in (:myCats)")
            ->andWhere('s.lat BETWEEN :minLat AND :maxLat ')
            ->andWhere('s.lon BETWEEN :minLon AND :maxLon ')
//                ->andWhere( 'distance <> 500'  )
            ->setParameters([
                'myCats' => $myCats,
                'myLat' => $myLat,
                'myLon' => $myLon,
                'maxLat' => $myLat + 30,
                'maxLon' => $myLon + 30,
                'minLat' => $myLat - 30,
                'minLon' => $myLon - 30,
                'myId' => $myId,
            ])
            ->orderBy('distance', 'DESC');//TODO DOES NOT WORK!!

        $query = $qb->getQuery();
        $allMatchesByOneMyService = $query->execute();

        return $allMatchesByOneMyService;
    }


//    public function findServiceUserByServiceId($serviceId)
//    {
//        $qb = $this->createQueryBuilder('s')
//            ->select('s')
//            ->where('s.id = :serviceId')
//            ->leftJoin('s.userId', 'u')
//            ->setParameter('serviceId', $serviceId)
//            ->getQuery();
//        $serviceAndUser = $qb->getOneOrNullResult();;
//
//        return $serviceAndUser;
//    }
}