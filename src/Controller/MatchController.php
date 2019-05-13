<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.27
 * Time: 12.27
 */

namespace App\Controller;


use App\Form\MatchType;
use App\Form\ServiceType;
use App\Helpers\MatchHelper;
use App\Match\Loader;
use App\Match\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/match/job/create/{callerJobId}/{responderServiceId}", name="match_job_create")
     */
    public function matchJobCreate( $callerJobId, $responderServiceId)
    {
        $em = $this->getDoctrine()->getManager();
        $manager = new Manager();
        $match = $manager->createJobMatch($callerJobId, $responderServiceId,  $em);
        $em->persist($match);
        $em->flush();
        return $this->redirectToRoute('match_by_jobs');
    }

    /**
     * @Route("/match/service/create/{callerServiceId}/{responderJobId}", name="match_service_create")
     */
    public function matchServiceCreate( $callerServiceId, $responderJobId)
    {
        $em = $this->getDoctrine()->getManager();
        $manager = new Manager();
        $match = $manager->createServiceMatch($callerServiceId, $responderJobId,  $em);
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
     * @Route("/match/{updateType}/{matchId}", name="matchUpdate")
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

//
//    //TODO Method to be removed because its web page is not required any more
//    /**
//     * @Route("match/show/{responderServiceId}/{callerServiceId}", name="matchShow")
//     */
//    public function showMatch($responderServiceId, $callerServiceId, Request $request)
//    {
//        $responderServiceJoinUser = $this->getDoctrine()
//            ->getRepository('App:Service')
//            ->findServiceUserByServiceId($responderServiceId);
//
//        $callerServiceJoinUser = $this->getDoctrine()
//            ->getRepository('App:Service')
//            ->findServiceUserByServiceId($callerServiceId);
//
//
//        return $this->render('match/show-match.html.twig', [
//            'responder' => $responderServiceJoinUser,
//            'caller' => $callerServiceJoinUser,
//        ]);
//    }


}