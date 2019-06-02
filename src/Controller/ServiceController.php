<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 11.31
 */

namespace App\Controller;

use App\Form\ServiceType;
use App\Service\Loader as ServiceLoader;
use App\Match\Loader as MatchLoader;
use App\Service\ServiceValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServiceController
 * @package App\Controller
 */
class ServiceController extends AbstractController
{

    private $em;
    private $serviceLoader;
    private $matchLoader;
    private $validator;

    /**
     * JobController constructor.
     * @param EntityManagerInterface $em
     * @param ServiceLoader $serviceLoader
     * @param MatchLoader $matchLoader
     * @param ServiceValidator $validator
     */
    public function __construct(EntityManagerInterface $em, ServiceLoader $serviceLoader, MatchLoader $matchLoader, ServiceValidator $validator)
    {
        $this->em = $em;
        $this->serviceLoader = $serviceLoader;
        $this->matchLoader = $matchLoader;
        $this->validator = $validator;

    }

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
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/service/edit/{id}", name="serviceEdit")
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editService(Request $request, int $id)
    {
        $userId = $this->getUser()->getId();
        $service = $this->serviceLoader->getService($id);
        $editRequestValid = $this->validator->checkEditValidity($id, $userId);
        if ($editRequestValid['validity']) {
            $form = $this->createForm(ServiceType::class, $service);
            $form->handleRequest($request);
        } else {
            $this->addFlash("danger", $editRequestValid['message']);
            return $this->redirectToRoute('my_services');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash("success", $editRequestValid['message']);
            return $this->redirectToRoute('my_services');
        }

        return $this->render('service/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $service->getId(),
        ]);
    }


    /**
     * @Route("/service/delete/{serviceId}", name="serviceDelete")
     */
    public function deleteService($serviceId)
    {
        $userId = $this->getUser()->getId();
        $deleteRequestValid = $this->validator->checkDeleteValidity($serviceId, $userId);
        if ($deleteRequestValid['validity']) {
            $this->serviceLoader->delete($serviceId);
            $this->addFlash("success", $deleteRequestValid['message']);
        } else {
            $this->addFlash("danger", $deleteRequestValid['message']);
        }

        return $this->redirectToRoute('my_services');
    }

    /**
     *
     * @Route("service/myservices", name="my_services")
     */
    public function listMyServices()
    {
        $userId = $this->getUser()->getId();
        $myServices = $this->serviceLoader->loadByUser($userId);

        return $this->render('service/my-services.html.twig', [
            'services' => $myServices,
        ]);
    }

    /**
     * @Route("service/pot-matches", name="service_pot_matches")
     */
    public function listPotMatches()
    {
        $userId = $this->getUser()->getId();
        $myMatchingJobs = $this->serviceLoader->loadPotMatches($userId);

        return $this->render('service/pot-matches.html.twig', [
            'potMatchesByServices' => $myMatchingJobs,
        ]);
    }
}
