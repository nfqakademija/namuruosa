<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 11.31
 */

namespace App\Controller;


use App\Form\ServiceType;
use App\Helpers\ServiceHelper;
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
            return $this->redirectToRoute('serviceAdd');
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
        $services = [$this->getDoctrine()->getRepository('App:Service')->getByUserIdQuery($userId)];
        return $this->render('service/list.html.twig', [
            'servicesArray'=>$services,
    ]);
    }


    /**
     * @Route("service/list-matches", name="listMatches")
     */
    public function listMatches()
    {
        $userId = $this->getUser()->getId();
        $myServices = $this->getDoctrine()->getRepository('App:Service')->getMatchesQuery($userId);

        return $this->render('service/list-matches.html.twig', [
            'servicesArray'=>$myServices,
        ]);


    }

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
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName()
        ]);
    }
}
