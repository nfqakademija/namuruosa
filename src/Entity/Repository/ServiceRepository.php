<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 08.19
 */

namespace App\Entity\Repository;


use App\Entity\Service;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ServiceRepository extends EntityRepository
{

//    public function __construct(RegistryInterface $registry)
//    {
//        parent::__construct($registry, Service::class);
//    }

    public function getByUserId($id)
    {
        return $this->getEntityManager()->createQuery(
            "
            SELECT s 
                FROM App\Entity\Service s 
            WHERE s.userId= :id 
            ORDER BY s.description DESC
            "
        )
            ->setParameter('id', $id)
            ->getResult();
    }

    public function getMatches($id){

        $myServices = $this->getByUserId($id);
        $matches =[];
        $qb = $this->createQueryBuilder('s');


        /**
         * @var $myServices Service[]
         */
        foreach ($myServices as $service){
            $myTimeStart = $service->getActiveTimeStart();
            $myTimeEnd = $service->getActiveTimeEnd();
            $myTransport = $service->getTransport();
            $myCleaning = $service->getCleaning();
            $myEducation = $service->getEducation();
            $myCoordX = $service->getCoordinateX();
            $myCoordY = $service->getCoordinateY();

//            $matches = $this->findBy([
//                'userId' => 2,
////                'activeTimeEnd'=>$myTimeEnd,
//            ]);

            $qb = $this->createQueryBuilder('s')
                ->where('s.userId = 1')
//                ->setParameter('transport', $myTransport)
                ->orderBy('s.activeTimeEnd')
                ->getQuery();

            $matches = $qb->execute();
        }
        return $matches;
    }
}