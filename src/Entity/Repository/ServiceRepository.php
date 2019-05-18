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
            ->addSelect('( (s.lat - :myLat) * (s.lat - :myLat) + (s.lon - :myLon) * (s.lon - :myLon)) / 100 AS HIDDEN distance')// Distance for sorting purpose ONLY
            ->andWhere('s.userId <> :myId')
            ->leftJoin('s.category', 'category')
            ->andWhere("category in (:myCats)")
            ->andWhere('s.lat BETWEEN :minLat AND :maxLat ')
            ->andWhere('s.lon BETWEEN :minLon AND :maxLon ')
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
            ->orderBy('distance', 'ASC');

        $query = $qb->getQuery();
        $allMatchesByOneMyService = $query->execute();

        return $allMatchesByOneMyService;
    }
}
