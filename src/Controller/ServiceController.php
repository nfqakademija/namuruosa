<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 11.31
 */

namespace App\Controller;


use App\Form\MatchType;
use App\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

/**
 * Class ServiceController
 * @package App\Controller
 *
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/service/add", name="serviceAdd")
     */
    public function addService(Request $request)
    {
        $form = $this->createForm(ServiceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $service = $form->getData();
            $service->setUserId($this->getUser());
            $em->persist($service);
            $em->flush();
            return $this->redirectToRoute('listMatches');
        }

        return $this->render('service/add.html.twig', [
            'serviceForm' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("service/list", name="serviceList")
     */
    public function listServices()
    {
        $userId = $this->getUser()->getId();
        $userServices = $this->getDoctrine()->getRepository('App:Service')->findServicesByUserId($userId);
        $services = [$this->getDoctrine()->getRepository('App:Service')->getByUserIdQuery($userId)];
        return $this->render('service/list.html.twig', [
            'servicesArray'=>[$userServices],
    ]);
    }

    /**
     * @Route("service/list-matches", name="listMatches")
     */
    public function listMatches()
    {
        $userId = $this->getUser()->getId();
        $userServices = $this->getDoctrine()
            ->getRepository('App:Service')
            ->findServicesByUserId($userId);
        $myMatchingServices = $this->getDoctrine()
            ->getRepository('App:Service')
            ->findMatches($userServices);
        $myServices = $this->getDoctrine()->getRepository('App:Service')->getMatchesQuery($userId);

        return $this->render('service/list-matches.html.twig', [
            'servicesArray'=>$myMatchingServices,
        ]);
    }

//    /**
//     * @Route("service/show-match/{responderServiceId}/{myServiceId}", name="showMatch")
//     */
//    public function showMatch($responderServiceId, $myServiceId, Request $request)
//    {
//        $responderServiceJoinUser = $this->getDoctrine()
//            ->getRepository('App:Service')
//            ->findServiceUserByServiceId($responderServiceId);
//
//        $callerServiceJoinUser = $this->getDoctrine()
//            ->getRepository('App:Service')
//            ->findServiceUserByServiceId($myServiceId);
//
//
//        $forma = $this->createForm(MatchType::class);
////        $forma->handleRequest($request);
//
//        return $this->render('service/show-match.html.twig', [
////            'matchform' => $forma->createView(),
//            'responder'=>$responderServiceJoinUser,
//            'caller'=>$callerServiceJoinUser,
//        ]);
//    }
    /**
     * @Route("service/show-match/{id}", name="showMatch")
     */
    public function showMatch($id)
    {
        $serviceId = $id;
        $service = $this->getDoctrine()->getRepository('App:Service')->find($serviceId);

        $userId = $service->getUserId();

        $user = $this->getDoctrine()->getRepository('App:User')->find($userId);


        return $this->render('service/show-match.html.twig', [
            'service'=>$service,
            'username' => $user->getUsername(),
            'id' => $user->getId(),

        ]);
    }
}