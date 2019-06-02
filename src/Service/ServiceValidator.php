<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.6.1
 * Time: 12.51
 */

namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use App\Service\Loader as ServiceLoader;
use App\Match\Loader as MatchLoader;

class ServiceValidator
{

    private $em;
    private $matchLoader;
    private $serviceLoader;

    /**
     * @param EntityManagerInterface $em
     * @param serviceLoader $serviceLoader
     * @param matchLoader $matchLoader
     */
    public function __construct(EntityManagerInterface $em, ServiceLoader $serviceLoader, MatchLoader $matchLoader)
    {
        $this->em = $em;
        $this->serviceLoader = $serviceLoader;
        $this->matchLoader = $matchLoader;
    }

    public function checkEditValidity($serviceId, $userId)
    {
        $result = [
            'validity' => false,
            'message' => '',
        ];
        $serviceMatches = $this->matchLoader->getMatchesByService($serviceId);
        $service = $this->serviceLoader->getService($serviceId);
        if ($service == null) {
            $result['message'] = 'Tokia paslauga neegzistuoja!';
        } elseif ($userId !== $service->getUserId()->getId()) {
            $result['message'] = "NEGALIMA keisti kitų vartotojų paslaugų!";
        } elseif ($serviceMatches !== []) {
            $result['message'] = "NEGALIMA keisti paslaugų, kurios dalyvauja sandoryje! Pirma turite pašalinti sandorius.";
        } else {
            $result['validity'] = true;
            $result['message'] = "Paslauga sėkmingai atnaujinta";
        }

        return $result;
    }

    public function checkDeleteValidity($serviceId, $userId)
    {
        $result = $this->checkEditValidity($serviceId, $userId);
        if ($result['validity']) {
            $result['message'] = "Paslauga sėkmingai pašalinta";
        }

        return $result;
    }
}
