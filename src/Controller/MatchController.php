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
    public function matchCreate( $callerJobId, $responderServiceId)
    {
        $em = $this->getDoctrine()->getManager();
        $manager = new Manager();
        $match = $manager->createJobMatch($callerJobId, $responderServiceId,  $em);
        $em->persist($match);
        $em->flush();
        return $this->redirectToRoute('matchListMyAll');
    }

//
//    /**
//     * @Route("/match/create/{responderServiceId}/{callerServiceId}", name="matchCreate")
//     */
//    public function matchCreateOrig($responderServiceId, $callerServiceId)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $manager = new Manager();
//        $match = $manager->createMatch($responderServiceId, $callerServiceId, $em);
//        $em->persist($match);
//        $em->flush();
//        return $this->redirectToRoute('matchListMyAll');
//    }


    /**
     * @Route("/match/by-jobs", name="match_by_jobs")
     */
    public function matchByJobs()
    {
        $myId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

        $loader = new Loader($em);

        $myJobsMatches = $loader->getJobMatches($myId);


//        $callsToMe = $this->getDoctrine()->getRepository('App:Match')->findBy(['responderId' => $myId]);
        return $this->render('match/listMyAll.html.twig', [
            'myCalls' => $myJobsMatches,
//            'callsToMe' => $callsToMe,
        ]);
    }



    /**
     * @Route("/match/{updateType}/{matchId}", name="matchUpdate")
     */
    public function matchUpdate($updateType, $matchId)
    {
        $em = $this->getDoctrine()->getManager();
        $helper = new MatchHelper();
        $match = $helper->updateMatch($updateType, $matchId, $em);
        if (!$match) {
            return $this->redirectToRoute('error', ['message' => 'nooperation']);
            }
        $em->persist($match);
        $em->flush();
        return $this->redirectToRoute('matchListMyAll');
    }

//        TODO Method to be replaced by matches_jobs and matches_services
//    /**
//     * @Route("/match/listMyAll", name="matchListMyAll")
//     */
//    public function matchListMyAllOrig()
//    {
//        $myId = $this->getUser()->getId();
//        $myCalls = $this->getDoctrine()->getRepository('App:Match')->findBy(['callerId' => $myId]);
//
//        $callsToMe = $this->getDoctrine()->getRepository('App:Match')->findBy(['responderId' => $myId]);
//        return $this->render('match/listMyAll.html.twig', [
//            'myCalls' => $myCalls,
//            'callsToMe' => $callsToMe,
//        ]);
//    }


    //TODO Method to be removed because its web page is not required any more
    /**
     * @Route("match/show/{responderServiceId}/{callerServiceId}", name="matchShow")
     */
    public function showMatch($responderServiceId, $callerServiceId, Request $request)
    {
        $responderServiceJoinUser = $this->getDoctrine()
            ->getRepository('App:Service')
            ->findServiceUserByServiceId($responderServiceId);

        $callerServiceJoinUser = $this->getDoctrine()
            ->getRepository('App:Service')
            ->findServiceUserByServiceId($callerServiceId);


        return $this->render('match/show-match.html.twig', [
            'responder' => $responderServiceJoinUser,
            'caller' => $callerServiceJoinUser,
        ]);
    }


}