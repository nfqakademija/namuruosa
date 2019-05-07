<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 08.19
 */

namespace App\Entity\Repository;


use App\Entity\Service;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ServiceRepository extends EntityRepository
{

// Method to find all services of a given user by ID
    public function findServicesByUserId($id)
    {
        return $this->getEntityManager()->createQuery(
            "
            SELECT s
                FROM App\Entity\Service s
            WHERE s.userId= :id
            ORDER BY s.updatedAt DESC
            "
        )
            ->setParameter('id', $id)
            ->getResult();
    }

// Method to find all services provided by others, that match given array of My services
    public function findMatches( $job)
    {


//    public function getMatchesQuery($id){

//        $myServices = $this->findServicesByUserId($id);
//        $matchesWithSearchTitle =[];


//            $myTimeStart = $service->getActiveTimeStart()->format('H:i');
//            $myTimeEnd = $service->getActiveTimeEnd()->format('H:i');
            $myCat1 = $job->getCategory1();
            $myCat2 = $job->getCategory2();
//            $myEducation = $service->getEducation();
            $myLat = $job->getLat();
            $myLon = $job->getLon();
//            $myServiceTitle = $service->getTitle();
            $myId = $job->getUserId()->getId();
//            $myServiceId = $service->getId();
//            $myId2 = $service->getUserId()->getId();

            $coordinateTollerance = 50;  //TODO replace with form field

//            $myCoordXmin = $myCoordX - $coordinateTollerance;
//            $myCoordXmax = $myCoordX + $coordinateTollerance;
//            $myCoordYmin = $myCoordY - $coordinateTollerance;
//            $myCoordYmax = $myCoordY + $coordinateTollerance;



//            $myServiceTitle = $service->getTitle();
//            $matchesWithSearchTitle =[];
//
            $qb = $this->createQueryBuilder('s')
                ->select('s')
                ->addSelect('ST_Distance_Sphere(
                              point(s.coordinateY, :myLon),
                              point(s.coordinateX, :myLat)
                              ) AS distance')// Distance just for sorting, not for real values
//                ->where('s.transport = :myTransport')
                ->where('s.userId <> :myId')
                ->andWhere('s.cleaning = :myCat1')
                ->orWhere('s.education = :myCat2')
                ->andWhere('s.coordinateX BETWEEN :myLat AND 100')
                ->andWhere('s.coordinateY BETWEEN :myLon AND 100')
//                ->andWhere('distance < 500'  )
                ->setParameters([
//                    'myTimeStart' => $myTimeStart,
//                    'myTimeEnd' => $myTimeEnd,
//                    'myTransport' => $myTransport,
                    'myCat1' => $myCat1,
                    'myCat2' => $myCat2,
                    'myLat' => $myLat,
                    'myLon' => $myLon,
//                    'myCoordY1' => $myCoordY,
//                    'myCoordY2' => $myCoordY,
//                    'myCoordXmin' => $myCoordXmin,
//                    'myCoordXmax' => $myCoordXmax,
//                    'myCoordYmin' => $myCoordYmin,
//                    'myCoordYmax' => $myCoordYmax,
                    'myId' => $myId,
                ])
                ->orderBy('distance', 'DESC');//TODO DOES NOT WORK!!

//            $qb->andWhere($qb->expr()->orX(
//                $qb->expr()->andX(
//                    's.activeTimeStart <= :myTimeStart', 's.activeTimeEnd >= :myTimeStart'),
//                $qb->expr()->andX(
//                    's.activeTimeStart <= :myTimeEnd', 's.activeTimeEnd >= :myTimeEnd'),
//                $qb->expr()->andX(
//                    's.activeTimeStart >= :myTimeStart', 's.activeTimeEnd <= :myTimeEnd')
//            ));

            $query = $qb->getQuery();
            $allMatchesByOneMyService = $query->execute();
////TODO remove addition of extra data to other method or class in the future
//
//            $additionalData = [];
//            $additionalData[] = $myServiceTitle;
//            $additionalData[] = $myTimeStart;
//            $additionalData[] = $myTimeEnd;
//            $additionalData[] = $myServiceId;
//
//            $matchesAndAdditionalByOneServices = [];
//            $matchesAndAdditionalByOneServices[] = $allMatchesByOneMyService;
//            $matchesAndAdditionalByOneServices[] = $additionalData;

//dump($allMatchesByOneMyService);

        return $allMatchesByOneMyService;
    }

// Method to find service and its owning user by given Service ID
    public function findServiceUserByServiceId($serviceId)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.id = :serviceId')
            ->leftJoin('s.userId', 'u')
            ->setParameter('serviceId', $serviceId)
            ->getQuery();
        $serviceAndUser = $qb->getOneOrNullResult();;

        return $serviceAndUser;
    }
}
