<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.5
 * Time: 08.53
 */

namespace App\Service;


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


    public function loadPotMatches($userId)
    {
        $potMatches = [];
        $myServices = $this->loadByUser($userId);

        foreach ($myServices as $myService) {
            $jobsByService = [];
            $jobsByService[] = $myService;
            $jobsByService[] = $this->em->getRepository('App:Job')
                ->findMatches($myService);
            $potMatches[] = $jobsByService;
        }
        return $potMatches;
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