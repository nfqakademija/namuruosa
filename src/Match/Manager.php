<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.12
 * Time: 11.26
 */

namespace App\Match;


use App\Entity\Match;
use Doctrine\ORM\EntityManager;

class Manager
{
    public function createJobMatch( $callerJobId, $responderServiceId, EntityManager $em)
    {
        $jobRepo = $em->getRepository('App\Entity\Job');
        $serviceRepo = $em->getRepository('App\Entity\Service');
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

    public function createServiceMatch( $callerServiceId, $responderJobId, EntityManager $em)
    {
        $jobRepo = $em->getRepository('App\Entity\Job');
        $serviceRepo = $em->getRepository('App\Entity\Service');
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




//    public function createMatch($responderServiceId, $callerServiceId, EntityManager $em)
//    {
//        $serviceRepo = $em->getRepository('App\Entity\Service');
//        $responderService = $serviceRepo->find($responderServiceId);
//        $callerService = $serviceRepo->find($callerServiceId);
//        $match = new Match();
//        $match->setCallerId($callerService->getUserId());
//        $match->setCallerServiceId($callerService);
//        $match->setResponderId($responderService->getUserId());
//        $match->setResponderServiceId($responderService);
////        $match->setCreatedAt(new \DateTime('Now'));
//
////        var_dump($match);
////        exit();
//
//        return($match);
//    }

}