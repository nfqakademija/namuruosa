<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.12
 * Time: 11.26
 */

namespace App\Match;


use App\Entity\Match;
use App\Helpers\Calculations;
use Doctrine\ORM\EntityManager;

class Manager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Manager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createJobMatch($callerJobId, $responderServiceId)
    {
        $jobRepo = $this->em->getRepository('App\Entity\Job');
        $serviceRepo = $this->em->getRepository('App\Entity\Service');
        $responderService = $serviceRepo->find($responderServiceId);
        $callerJob = $jobRepo->find($callerJobId);
        $match = new Match();
        $match->setCallerId($callerJob->getUserId());
        $match->setCallerJobId($callerJob);
        $match->setResponderId($responderService->getUserId());
        $match->setResponderServiceId($responderService);
        $match->setHidden(false);

        return($match);
    }

    public function createServiceMatch($callerServiceId, $responderJobId, Calculations $distCalculator)
    {
        $jobRepo = $this->em->getRepository('App\Entity\Job');
        $serviceRepo = $this->em->getRepository('App\Entity\Service');
        $callerService = $serviceRepo->find($callerServiceId);
        $responderJob = $jobRepo->find($responderJobId);
        $match = new Match();
        $match->setCallerId($callerService->getUserId());
        $match->setCallerServiceId($callerService);
        $match->setResponderId($responderJob->getUserId());
        $match->setResponderJobId($responderJob);


        $match->setHidden(false);

        return($match);
    }

//    public function getDistance($lat1, $lon1, $lat2, $lon2, Calculations $distCalculator)
//    {
//        return
//    }
}
