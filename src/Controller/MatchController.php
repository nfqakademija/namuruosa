<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.27
 * Time: 12.27
 */

namespace App\Controller;

use App\Entity\Match;
use App\Helpers\MatchHelper;
use App\Match\Loader;
use App\Match\Manager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    private $em;
    private $loader;

    /**
     * JobController constructor.
     * @param EntityManagerInterface $em
     * @param Loader $loader
     */
    public function __construct(EntityManagerInterface $em, Loader $loader)
    {
        $this->em = $em;
        $this->loader = $loader;
    }


    /**
     * @Route("/match/job/create/{callerJobId}/{responderServiceId}", name="match_job_create")
     */
    public function matchJobCreate($callerJobId, $responderServiceId)
    {
        $em = $this->getDoctrine()->getManager();
        $manager = new Manager($em);
        $match = $manager->createJobMatch($callerJobId, $responderServiceId, $em);
        $em->persist($match);
        $em->flush();
        return $this->redirectToRoute('match_by_jobs');
    }

    /**
     * @Route("/match/service/create/{callerServiceId}/{responderJobId}", name="match_service_create")
     */
    public function matchServiceCreate($callerServiceId, $responderJobId)
    {
        $em = $this->getDoctrine()->getManager();
        $manager = new Manager($em);
        $match = $manager->createServiceMatch($callerServiceId, $responderJobId);
        $em->persist($match);
        $em->flush();
        return $this->redirectToRoute('match_by_services');
    }

    /**
     * @Route("/match/by-jobs", name="match_by_jobs")
     */
    public function matchByJobs()
    {
        $myId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $loader = new Loader($em);
        $myJobsMatches = $loader->getJobMatches($myId);

        return $this->render('match/my-jobs-matches.twig', [
            'myJobsMatches' => $myJobsMatches,
        ]);
    }

    /**
     * @Route("/match/by-services", name="match_by_services")
     */
    public function matchByServices()
    {
        //Todo likusi dalis
        $myId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $loader = new Loader($em);
        $myServicesMatches = $loader->getServicesMatches($myId);

        return $this->render('match/my-services-matches.twig', [
            'myServicesMatches' => $myServicesMatches,
        ]);
    }

    /**
     * @Route("/match/update/{matchId}/{updateType}", name="matchUpdate")
     */
    public function matchUpdate($updateType, $matchId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $helper = new MatchHelper();
        $match = $helper->updateMatch($updateType, $matchId, $em);
        if (!$match) {
            return $this->redirectToRoute('error', ['message' => 'nooperation']);
        }
        $em->persist($match);
        $em->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @Route("/match/delete/{matchId}", name="matchDelete")
     */

    public function deleteMatch($matchId, Request $request)
    {
        $this->loader->delete($matchId);
        $referer = $request->headers->get('referer');
        return $this->redirectToRoute('match_by_jobs');
    }
}
