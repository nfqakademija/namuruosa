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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/match/create/{responderServiceId}/{callerServiceId}", name="matchCreate")
     */
    public function matchCreate($responderServiceId, $callerServiceId)
    {
        $em = $this->getDoctrine()->getManager();
        $helper = new MatchHelper();
        $match = $helper->createMatch($responderServiceId, $callerServiceId, $em);
        $em->persist($match);
        $em->flush();
        return $this->redirectToRoute('matchListMyAll');
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


    /**
     * @Route("/match/listMyAll", name="matchListMyAll")
     */
    public function matchListMyAll()
    {
        $myId = $this->getUser()->getId();
        $myCalls = $this->getDoctrine()->getRepository('App:Matches')->findBy(['callerId' => $myId]);

        $callsToMe = $this->getDoctrine()->getRepository('App:Matches')->findBy(['responderId' => $myId]);
        return $this->render('match/listMyAll.html.twig', [
            'myCalls' => $myCalls,
            'callsToMe' => $callsToMe,
        ]);
    }

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