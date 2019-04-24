<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.23
 * Time: 08.16
 */

namespace App\Helpers;


use App\Entity\Repository\ServiceRepository;
use App\Entity\Service;
use Doctrine\ORM\EntityManager;

class ServiceHelper
{

    public function getMatches(array $myServices, $myId)
    {

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