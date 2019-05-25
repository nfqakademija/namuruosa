<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.4
 * Time: 21.01
 */

namespace App\Entity\Repository;


use App\Entity\Service;
use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository
{

    public function findByUserId($userId)
    {
        return $this->getEntityManager()->createQuery(
            " SELECT s 
            FROM App\Entity\Job s 
            WHERE s.userId= :id 
            ORDER BY s.updatedAt DESC "
        )
            ->setParameter('id', $userId)
            ->getResult();
    }


    public function findMatches(Service $service)
    {

        $myCats = $service->getCategory()->toArray();
        $myLat = $service->getLat();
        $myLon = $service->getLon();
        $myId = $service->getUserId()->getId();

        $qb = $this->createQueryBuilder('j')
            ->addSelect('( (j.lat - :myLat) * (j.lat - :myLat) + (j.lon - :myLon) * (j.lon - :myLon)) / 100 AS HIDDEN distance')// Distance for sorting purpose ONLY
            ->andWhere('j.userId <> :myId')
            ->leftJoin('j.category', 'category')
            ->andWhere("category in (:myCats)")
            ->andWhere('j.lat BETWEEN :minLat AND :maxLat ')
            ->andWhere('j.lon BETWEEN :minLon AND :maxLon ')
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
        $allMatchesByOneMyJob = $query->execute();

        return $allMatchesByOneMyJob;
    }

    public function getAllJobs()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
                SELECT 
                       j.id, j.title, j.created_at, j.date_end, j.user_id,
                       u.username, u.first_name, u.last_name
                FROM job j
                    LEFT JOIN fos_user u
                        ON j.user_id = u.id";

        $query = $conn->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}