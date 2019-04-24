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

class ServiceRepository extends EntityRepository
{

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

        /**
         * @var $myServices Service[]
         */
        foreach ($myServices as $service){
            $myTimeStart = $service->getActiveTimeStart();
            $myTimeEnd = $service->getActiveTimeEnd();
            $myTransport = $service->getTransport();
            $myCleaning = $service->getCleaning();
            $myEducation = $service->getEducation();
            $myCoordX = $service->getCoordinateX();
            $myCoordY = $service->getCoordinateY();

            echo($myCoordX);
        }

    }
}