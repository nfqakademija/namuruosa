<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 11.31
 */

namespace App\Controller;

use App\Form\ServiceType;
use App\Service\Loader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $service = $form->getData();
            $service->setUserId($this->getUser());
            $em->persist($service);
            $em->flush();
            return $this->redirectToRoute('service_pot_matches');
        }

        return $this->render('service/add.html.twig', [
            'serviceForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/service/delete/{serviceId}", name="serviceDelete")
     */
    public function deleteJob($serviceId, Request $request, Loader $loader)
    {
        $loader->delete($serviceId);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     *
     * @Route("service/myservices", name="my_services")
     */
    public function listMyJobs(Loader $loader)
    {
        $userId = $this->getUser()->getId();
        $myServices = $loader->loadByUser($userId);

        return $this->render('service/my-services.html.twig', [
            'servicesArray' => [$myServices],
        ]);
    }

    /**
     * @Route("service/pot-matches", name="service_pot_matches")
     */
    public function listPotMatches(Loader $loader)
    {
        $userId = $this->getUser()->getId();
        $myMatchingJobs = $loader->loadPotMatches($userId);

        return $this->render('service/pot-matches.html.twig', [
            'potMatchesByServices' => $myMatchingJobs,
        ]);
    }
}
