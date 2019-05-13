<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.27
 * Time: 21.00
 */

namespace App\Helpers;


use App\Entity\Match;
//use App\Entity\Match;
use App\Entity\Repository\MatchRepository;
use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\EntityManager;

class MatchHelper
{

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
//       return($match);
//    }

    public function updateMatch($updateType, $matchId, EntityManager $em)
    {
        $matchesRepo = $em->getRepository('App\Entity\Match');
//        $responderService = $serviceRepo->find($responderServiceId);
//        $callerService = $serviceRepo->find($callerServiceId);
        $match = $matchesRepo->find($matchId);
        $now = new \DateTime('Now');
        if ($updateType === 'accept'){
            $match->setAcceptedAt($now);
        } else if ($updateType === 'reject'){
            $match->setRejectedAt($now);
        } else if ($updateType === 'cancel'){
            $match->setCancelledAt($now);
        } else {
            return null;
        }

//        $match->setCallerServiceId($callerService);
//        $match->setResponderId($responderService->getUserId());
//        $match->setResponderServiceId($responderService);
//        $match->setCreatedAt(new \DateTime('Now'));

//        var_dump($match);
//        exit();

        return($match);
    }
}