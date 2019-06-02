<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.12
 * Time: 14.09
 */

namespace App\Match;

use Doctrine\ORM\EntityManagerInterface;

class Loader
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Loader constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getMatch($matchId)
    {
        return $this->em->find('App:Match', $matchId);
    }

    public function getJobMatches($userId)
    {
        return $this->em->getRepository('App:Match')->findJobsMatches($userId);
    }

    public function getServicesMatches($userId)
    {
        return $this->em->getRepository('App:Match')->findServicesMatches($userId);
    }

    public function updateMatch($updateType, $matchId)
    {
        $match = $this->getMatch($matchId);
        $now = new \DateTime('Now');
        if ($updateType === 'accept') {
            $match->setAcceptedAt($now);
        } elseif ($updateType === 'reject') {
            $match->setRejectedAt($now);
        } elseif ($updateType === 'cancel') {
            $match->setCancelledAt($now);
        } else {
            return null;
        }
        return ($match);
    }

    public function delete($matchId)
    {
        $match = $this->getMatch($matchId);
        if ($match) {
            $this->em->remove($match);
            $this->em->flush();
            return true;
        } else {
            return false;
        }
    }

    public function getMatchesByJob($jobId)
    {
        return $this->em->getRepository('App:Match')->findJobMatchesByJobId($jobId);
    }

    public function getMatchesByService($serviceId)
    {
        return $this->em->getRepository('App:Match')->findServiceMatchesByServiceId($serviceId);
    }
}
