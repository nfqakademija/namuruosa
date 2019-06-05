<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.5
 * Time: 08.53
 */

namespace App\Service;

use App\Entity\Job;
use App\Entity\Service;
use App\Helpers\CalcHelper;
use App\Profile\DataLoader;
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
     * @var dataLoader
     */
    private $profileLoader;

    /**
     * Loader constructor.
     * @param EntityManagerInterface $em
     * @param CalcHelper $calcDist
     * @param DataLoader $profileLoader
     */
    public function __construct(EntityManagerInterface $em, CalcHelper $calcDist, DataLoader $profileLoader)
    {
        $this->em = $em;
        $this->calcDist = $calcDist;
        $this->profileLoader = $profileLoader;
    }

    public function getService($serviceId)
    {
        return $this->em->find(Service::class, $serviceId);
    }

     /**
     * @param $id
     * @return mixed
     */
    public function loadByUserQuery($userId)
    {
        return $this->em->getRepository(Service::class)->findServicesByUserId($userId);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function loadByUser($userId)
    {
        return $this->loadByUserQuery($userId)->getResult();
    }

    /**
     * @return array
     */
    public function addDistanceAndRating($myService, $potMatches)
    {
        $lat = $myService->getLat();
        $lon = $myService->getLon();
        foreach ($potMatches as $job) {
            $serviceUserId = $job->getUserId()->getId();
            $job->setUserRating($this->profileLoader->getAverageRating($serviceUserId));
            $job->setDistance($this->calcDistance($lat, $lon, $job));
        }

        return $potMatches;
    }

    /**
     * @param $myServiceId int
     * @return array
     */
    public function getPotMatchesByServiceIdQuery($myServiceId)
    {
        $service = $this->em->getRepository(Service::class)->find($myServiceId);
        $potMatchesQuery = $this->em->getRepository(Job::class)->getMatchesQuery($service);

        return $potMatchesQuery;
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

    /**
     * @param $serviceId
     * @return bool
     */
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
