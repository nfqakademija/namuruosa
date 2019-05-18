<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.5
 * Time: 08.53
 */

namespace App\Service;


use App\Entity\Job;
use App\Helpers\CalcHelper;
use Doctrine\ORM\EntityManagerInterface;

class Loader
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CalcHelper
     */
    private $calcDist;

    /**
     * Loader constructor.
     * @param EntityManagerInterface $em
     * @param CalcHelper $calcDist
     */
    public function __construct(EntityManagerInterface $em, CalcHelper $calcDist)
    {
        $this->em = $em;
        $this->calcDist = $calcDist;
    }


    public function getService($serviceId)
    {
        return $this->em->find('App:Service', $serviceId);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function loadByUser($userId)
    {
        return $this->em->getRepository('App:Service')->findByUserId($userId);
    }

    /**
     * @param $userId int
     * @return array
     */
    public function loadPotMatches($userId)
    {
        $potMatches = [];
        $myServices = $this->loadByUser($userId);
        foreach ($myServices as $myService) {
            $jobsByService = [];
            $serviceLat = $myService->getLat();
            $serviceLon = $myService->getLon();
            $jobsByService[] = $myService;
            $jobs = $this->em->getRepository(Job::class)->findMatches($myService);
            foreach ($jobs as $job) {
                $job->setDistance($this->calcDistance($serviceLat, $serviceLon, $job));
            }
            $jobsByService[] = $jobs;
            $potMatches[] = $jobsByService;
        }
        return $potMatches;
    }

    /**
     * @param $serviceLat float
     * @param $serviceLon float
     * @param $job Job
     * @return float
     */
    private function calcDistance($serviceLat, $serviceLon, $job)
    {
        $jobLat = $job->getLat();
        $jobLon = $job->getLon();
        return $this->calcDist->getDistanceFromCoordinates($jobLat, $jobLon, $serviceLat, $serviceLon);
    }



    public function delete($serviceId)
    {
        $service = $this->getService($serviceId);
        if ($service) {
            $this->em->remove($service);
            $this->em->flush();
            return true;
        } else {
            return false;
        }
    }
}