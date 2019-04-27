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

    public function getByUserId($id)
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


    public function getMatches($id)
    {

        $myServices = $this->getByUserId($id);
        $matchesWithSearchTitle = [];

        /**
         * @var $myServices Service[]
         */
        foreach ($myServices as $service) {

            $myTimeStart = $service->getActiveTimeStart()->format('H:i');
            $myTimeEnd = $service->getActiveTimeEnd()->format('H:i');
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

            $matchesWithSearchTitle = [];

            $qb = $this->createQueryBuilder('s')
                ->addSelect('( (s.coordinateX - :myCoordX1) * (s.coordinateX - :myCoordX2) + (s.coordinateY - :myCoordY1) * (s.coordinateY - :myCoordY2)) AS HIDDEN distance')// Distance without sqrt - just for sorting, not for real values
                ->where('s.transport = :myTransport')
                ->orWhere('s.cleaning = :myCleaning')
                ->orWhere('s.education = :myEducation')
                ->andWhere('s.coordinateX BETWEEN :myCoordXmin AND :myCoordXmax')
                ->andWhere('s.coordinateY BETWEEN :myCoordYmin AND :myCoordYmax')
                ->andWhere('s.userId <> :myId')
//                ->andWhere('distance < 500'  )
                ->setParameters([
                    'myTimeStart' => $myTimeStart,
                    'myTimeEnd' => $myTimeEnd,
                    'myTransport' => $myTransport,
                    'myCleaning' => $myCleaning,
                    'myEducation' => $myEducation,
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
                ->orderBy('distance', 'ASC');//TODO Išsiaiškinti kodėl rikiuoja belekaip

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->andX(
                    's.activeTimeStart <= :myTimeStart', 's.activeTimeEnd >= :myTimeStart'),
                $qb->expr()->andX(
                    's.activeTimeStart <= :myTimeEnd', 's.activeTimeEnd >= :myTimeEnd'),
                $qb->expr()->andX(
                    's.activeTimeStart >= :myTimeStart', 's.activeTimeEnd <= :myTimeEnd')
            ));

            $query = $qb->getQuery();
            $matchesBySearch = $query->execute();

            $matchesWithSearchTitle[] = $myServiceTitle;
            $matchesWithSearchTitle[] = $myTimeStart;
            $matchesWithSearchTitle[] = $myTimeEnd;
            $matchesWithSearchTitle[] = $matchesBySearch;
            $matchesInfo[] = $matchesWithSearchTitle;

        }
        return $matchesInfo;
    }


    public function findServiceUserByServiceId($serviceId)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.id = :serviceId')
            ->leftJoin('s.userId', 'u')
//            -> addSelect('u.username')
            ->setParameter('serviceId', $serviceId)
            ->getQuery();

        $serviceAndUser = $qb->execute();

        return $serviceAndUser;
    }
}