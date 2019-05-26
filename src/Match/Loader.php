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
}
