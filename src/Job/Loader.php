<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.5
 * Time: 08.53
 */

namespace App\Job;


use App\Entity\Job;
use App\Entity\Service;
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
     * @param CalcHelper $calculator
     */
    public function __construct(EntityManagerInterface $em, CalcHelper $calDist)
    {
        $this->em = $em;
        $this->calcDist = $calDist;
    }


    public function getJob($jobId)
    {
        return $this->em->find('App:Job', $jobId);
    }

    /**
     * @param $userId
     * @return Job[]|null
     */
    public function loadByUser($userId): ?array
    {
        return $this->em->getRepository(Job::class)->findByUserId($userId);
    }

    /**
     * @param $userId int
     * @return array
     */
    public function loadPotMatches($userId)
    {
        $potMatches = [];
        $myJobs = $this->loadByUser($userId);
        foreach ($myJobs as $myJob) {
            $servicesByJob = [];
            $jobLat = $myJob->getLat();
            $jobLon = $myJob->getLon();
            $servicesByJob[] = $myJob;
            $services = $this->em->getRepository(Service::class)->findMatches($myJob);
            foreach ($services as $service) {
                $service->setDistance($this->calcDistance($jobLat, $jobLon, $service));
            }
            $servicesByJob[] = $services;
            $potMatches[] = $servicesByJob;
        }
        return $potMatches;
    }

    /**
     * @param $jobLat float
     * @param $jobLon float
     * @param $service Service
     * @return float
     */
    private function calcDistance($jobLat, $jobLon, $service)
    {
        $serviceLat = $service->getLat();
        $serviceLon = $service->getLon();
        return $this->calcDist->getDistanceFromCoordinates($jobLat, $jobLon, $serviceLat, $serviceLon);
    }


    public function delete($jobId)
    {
        $job = $this->getJob($jobId);
        if ($job) {
            $this->em->remove($job);
            $this->em->flush();
            return true;
        } else {
            return false;
        }
    }


}