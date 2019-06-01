<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.6.1
 * Time: 12.51
 */

namespace App\Job;


use Doctrine\ORM\EntityManagerInterface;
use App\Job\Loader;
use App\Match\Loader as MatchLoader;

class Validator
{

    private $em;
    private $loader;
    private $matchLoader;

    /**
     * @param EntityManagerInterface $em
     * @param Loader $loader
     * @param matchLoader $matchLoader
     */
    public function __construct(EntityManagerInterface $em, Loader $loader, MatchLoader $matchLoader)
    {
        $this->em = $em;
        $this->loader = $loader;
        $this->matchLoader = $matchLoader;
    }

    public function checkDeleteValidity($jobId, $userId)
    {
        $result = [
            'validity' => false,
            'message' => '',
        ];
        $jobMatches = $this->matchLoader->getMatchesByJob($jobId);
        $job = $this->loader->getJob($jobId);
        if ($job == null) {
            $result['message'] = 'Toks darbas neegzistuoja!';
        } elseif ($userId !== $job->getUserId()->getId()) {
            $result['message'] = "NEGALIMA šalinti kitų vartotojų darbų!";
        } elseif ($jobMatches !== []) {
            $result['message'] = "Negalima pašalinti darbo, kuris dalyvauja sandoryje! Pirma turite pašalinti šio darbo sandorius.";
        } else {
            $this->loader->delete($jobId);
            $result['validity'] = true;
            $result['message'] = "Darbas sėkmingai pašalintas";
        }

        return $result;
    }
}
