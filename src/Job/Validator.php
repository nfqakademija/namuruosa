<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.6.1
 * Time: 12.51
 */

namespace App\Job;


use Doctrine\ORM\EntityManagerInterface;
use App\Job\Loader as JobLoader;
use App\Service\Loader as ServiceLoader;
use App\Match\Loader as MatchLoader;

class Validator
{

    private $em;
    private $matchLoader;
    private $jobLoader;
    private $serviceLoader;

    /**
     * @param EntityManagerInterface $em
     * @param jobLoader $jobLoader
     * @param matchLoader $matchLoader
     */
    public function __construct(EntityManagerInterface $em, JobLoader $jobLoader, ServiceLoader $serviceLoader, MatchLoader $matchLoader)
    {
        $this->em = $em;
        $this->jobLoader = $jobLoader;
        $this->serviceLoader = $serviceLoader;
        $this->matchLoader = $matchLoader;
    }

    public function checkDeleteValidity($jobId, $userId)
    {
        $result = [
            'validity' => false,
            'message' => '',
        ];
        $jobMatches = $this->matchLoader->getMatchesByJob($jobId);
        $job = $this->jobLoader->getJob($jobId);
        if ($job == null) {
            $result['message'] = 'Toks darbas neegzistuoja!';
        } elseif ($userId !== $job->getUserId()->getId()) {
            $result['message'] = "NEGALIMA šalinti kitų vartotojų darbų!";
        } elseif ($jobMatches !== []) {
            $result['message'] = "Negalima pašalinti darbo, kuris dalyvauja sandoryje! Pirma turite pašalinti šio darbo sandorius.";
        } else {
            $this->jobLoader->delete($jobId);
            $result['validity'] = true;
            $result['message'] = "Darbas sėkmingai pašalintas";
        }

        return $result;
    }
}
