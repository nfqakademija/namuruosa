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
    private $em;

    /**
     * Loader constructor.
     * @param $em
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
        $myJobsMatches = $this->em->getRepository('App:Match')->findJobsMatches($userId);
        return $myJobsMatches;
    }

    public function getServicesMatches($userId)
    {
        $myServicesMatches = $this->em->getRepository('App:Match')->findServicesMatches($userId);
        return $myServicesMatches;
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
}
