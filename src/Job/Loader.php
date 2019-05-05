<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.5
 * Time: 08.53
 */

namespace App\Job;


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

    /**
     * @param $id
     * @return mixed
     */
    public function loadByUser($userId)
    {
        return $this->em->getRepository('App:Job')->findByUserId($userId);
    }


    public function loadPotMatches($userId)
    {
        $potMatches = [];
        $myJobs = $this->loadByUser($userId);

        foreach ($myJobs as $myJob) {
            $servicesByJob = [];
            $servicesByJob[] = $myJob;
            $servicesByJob[] = $this->em->getRepository('App:Service')
                ->findMatches($myJob);
            $potMatches[] = $servicesByJob;
        }
        return $potMatches;
    }




}