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
use Symfony\Bridge\Doctrine\RegistryInterface;

class ServiceRepository extends EntityRepository
{

//    public function __construct(RegistryInterface $registry)
//    {
//        parent::__construct($registry, Service::class);
//    }

    public function getByUserId($id)
    {
        return $this->getEntityManager()->createQuery(
            "
            SELECT s 
                FROM App\Entity\Service s 
            WHERE s.userId= :id 
            ORDER BY s.description DESC
            "
        )
            ->setParameter('id', $id)
            ->getResult();
    }



    public function getMatches($id){

        $myServices = $this->getByUserId($id);
        $matchesWithSearchTitle =[];

        /**
         * @var $myServices Service[]
         */
        foreach ($myServices as $service){

            $myTimeStart = $service->getActiveTimeStart()->format('H:i:s');
            $myTimeEnd = $service->getActiveTimeEnd()->format('H:i:s');
            $myTransport = $service->getTransport();
            $myCleaning = $service->getCleaning();
            $myEducation = $service->getEducation();
            $myCoordX = $service->getCoordinateX();
            $myCoordY = $service->getCoordinateY();
            $coordinateTollerance = 50;  //TODO replace with form field
            $myCoordXmin = $myCoordX - $coordinateTollerance;
            $myCoordXmax = $myCoordX + $coordinateTollerance;
            $myCoordYmin = $myCoordY - $coordinateTollerance;
            $myCoordYmax = $myCoordY + $coordinateTollerance;

            $myServiceTitle = $service->getTitle();
            $matchesWithSearchTitle =[];

            $qb = $this->createQueryBuilder('s')
                ->addSelect('( (s.coordinateX - :myCoordX1) * (s.coordinateX - :myCoordX2) + (s.coordinateY - :myCoordY1) * (s.coordinateY - :myCoordY2)) AS HIDDEN distance' ) // Distance without sqrt - just for sorting, not for real values
                ->where('s.transport = :myTransport')
                ->orWhere('s.cleaning = :myCleaning')
                ->orWhere('s.education = :myEducation')
                ->andWhere('s.activeTimeStart <= :myTimeStart')
                ->andWhere('s.activeTimeEnd <= :myTimeEnd')
                ->andWhere('s.coordinateX BETWEEN :myCoordXmin AND :myCoordXmax'  )
                ->andWhere('s.coordinateY BETWEEN :myCoordYmin AND :myCoordYmax'  )
                ->andWhere('s.userId <> :myId'  )
//                ->andWhere('distance < 500'  )
                ->setParameters([
                    'myTimeStart'=>$myTimeStart,
                    'myTimeEnd'=>$myTimeEnd,
                    'myTransport'=>$myTransport,
                    'myCleaning'=>$myCleaning,
                    'myEducation'=>$myEducation,
                    'myCoordX1' => $myCoordX,
                    'myCoordX2' => $myCoordX,
                    'myCoordY1' => $myCoordY,
                    'myCoordY2' => $myCoordY,
                    'myCoordXmin' => $myCoordXmin,
                    'myCoordXmax' => $myCoordXmax,
                    'myCoordYmin' => $myCoordYmin,
                    'myCoordYmax' => $myCoordYmax,
                    'myId' => $id,

                ])
                ->orderBy('distance', 'ASC') //TODO Išsiaiškinti kodėl rikiuoja belekaip
                ->getQuery();

            $matchesBySearch = $qb->execute();
//            $matchesWithSearchTitle[] = ['myTitle'=>$myServiceTitle];
            $matchesWithSearchTitle[] = $myServiceTitle;
            $matchesWithSearchTitle[] = $matchesBySearch;
            $allMatches[] = $matchesWithSearchTitle;

        }
//        var_dump($matches);
        return $allMatches;
    }
}