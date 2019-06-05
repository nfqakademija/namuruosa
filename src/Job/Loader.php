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
use App\Profile\DataLoader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;

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
     * @var dataLoader
     */
    private $profileLoader;

    /**
     * Loader constructor.
     * @param EntityManagerInterface $em
     * @param CalcHelper $calDist
     * @param DataLoader $profileLoader
     */
    public function __construct(EntityManagerInterface $em, CalcHelper $calDist, DataLoader $profileLoader, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->calcDist = $calDist;
        $this->profileLoader = $profileLoader;
    }

    public function getJob($jobId)
    {
        return $this->em->find('App:Job', $jobId);
    }

    /**
     * @param $userId
     * @return Query
     */
    public function loadQueryByUser($userId): ?Query
    {
        return $this->em->getRepository(Job::class)->findByUserId($userId);
    }

    /**
     * @param $userId
     * @return Job[]
     */
    public function loadByUser($userId)
    {
        return $this->loadQueryByUser($userId)->getResult();
    }

    /**
     * @return array
     */
    public function addDistanceAndRating($myJob, $potMatches)
    {
//        $potMatches = [];
//        $myJobs = $this->loadByUser($userId);
//        foreach ($myJobs as $myJob) {
//        $servicesByJob = [];
        $lat = $myJob->getLat();
        $lon = $myJob->getLon();
//        $servicesByJob[] = $myJob;
//            $services = $this->em->getRepository(Service::class)->findMatches($myJob);
        foreach ($potMatches as $service) {
            $serviceUserId = $service->getUserId()->getId();
            $service->setUserRating($this->profileLoader->getAverageRating($serviceUserId));
            $service->setDistance($this->calcDistance($lat, $lon, $service));
        }
//            $servicesByJob[] = $services;
//        $potMatches[] = $servicesByJob;
//        }
        return $potMatches;
    }

    /**
     * @param $myJobId int
     * @return array
     */
    public function getPotMatchesByJobIdQuery($myJobId)
    {
        $job = $this->em->getRepository(Job::class)->find($myJobId);
        $potMatchesQuery = $this->em->getRepository(Service::class)->getMatchesQuery($job);

        return $potMatchesQuery;
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
