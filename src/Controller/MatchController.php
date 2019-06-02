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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    private $em;
    private $loader;
    private $manager;
    private $paginator;


    /**
     * JobController constructor.
     * @param EntityManagerInterface $em
     * @param Loader $loader
     * @param Manager $manager
     */
    public function __construct(EntityManagerInterface $em, Loader $loader, Manager $manager, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->loader = $loader;
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/match/job/create/{callerJobId}/{responderServiceId}", name="match_job_create")
     */
    public function matchJobCreate($callerJobId, $responderServiceId)
    {
        $match = $this->manager->createJobMatch($callerJobId, $responderServiceId, $this->em);
        $this->em->persist($match);
        $this->em->flush();

        return $this->redirectToRoute('match_by_jobs');
    }

    /**
     * @Route("/match/service/create/{callerServiceId}/{responderJobId}", name="match_service_create")
     */
    public function matchServiceCreate($callerServiceId, $responderJobId)
    {
        $match = $this->manager->createServiceMatch($callerServiceId, $responderJobId);
        $this->em->persist($match);
        $this->em->flush();

        return $this->redirectToRoute('match_by_services');
    }

    /**
     * @Route("/match/by-jobs", name="match_by_jobs")
     */
    public function matchByJobs(Request $request)
    {
        $myId = $this->getUser()->getId();
        $myJobsMatchesQuery = $this->loader->getJobMatches($myId);
        $myJobsMatches = $this->paginator->paginate(
            $myJobsMatchesQuery,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)
        );

        return $this->render('match/my-jobs-matches.twig', [
            'myJobsMatches' => $myJobsMatches,
        ]);
    }

    /**
     * @Route("/match/by-services", name="match_by_services")
     */
    public function matchByServices()
    {
        $myId = $this->getUser()->getId();
        $myServicesMatches = $this->loader->getServicesMatches($myId);

        return $this->render('match/my-services-matches.twig', [
            'myServicesMatches' => $myServicesMatches,
        ]);
    }

    /**
     * @Route("/match/update/{matchId}/{updateType}", name="matchUpdate")
     */
    public function matchUpdate($updateType, $matchId, Request $request)
    {
        $match = $this->loader->updateMatch($updateType, $matchId);
        if (!$match) {
            return $this->redirectToRoute('error', ['message' => 'nooperation']);
        }
        $this->em->persist($match);
        $this->em->flush();
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

        return $this->redirect($referer);
    }
}
