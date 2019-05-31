<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.27
 * Time: 21.00
 */

namespace App\Helpers;

use Doctrine\ORM\EntityManager;

class MatchHelper
{
//    public function updateMatch($updateType, $matchId, EntityManager $em)
//    {
//        $matchesRepo = $em->getRepository('App\Entity\Match');
//        $match = $matchesRepo->find($matchId);
//        $now = new \DateTime('Now');
//        if ($updateType === 'accept') {
//            $match->setAcceptedAt($now);
//        } elseif ($updateType === 'reject') {
//            $match->setRejectedAt($now);
//        } elseif ($updateType === 'cancel') {
//            $match->setCancelledAt($now);
//        } else {
//            return null;
//        }
//        return ($match);
//    }
}

