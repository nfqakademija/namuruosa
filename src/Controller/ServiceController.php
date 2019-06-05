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
use Knp\Component\Pager\PaginatorInterface;
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
    private $paginator;

    /**
     * ServiceController constructor.
     * @param EntityManagerInterface $em
     * @param ServiceLoader $serviceLoader
     * @param MatchLoader $matchLoader
     * @param ServiceValidator $validator
     * @param PaginatorInterface $paginator
     */
    public function __construct(EntityManagerInterface $em, ServiceLoader $serviceLoader, MatchLoader $matchLoader, ServiceValidator $validator, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->serviceLoader = $serviceLoader;
        $this->matchLoader = $matchLoader;
        $this->validator = $validator;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/service/add", name="serviceAdd")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * @param $serviceId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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
     * @Route("service/myservices", name="my_services")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listMyServices(Request $request)
    {
        $userId = $this->getUser()->getId();
        $myServicesQuery = $this->serviceLoader->loadByUserQuery($userId);

        $myServices = $this->paginator->paginate(
            $myServicesQuery,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('service/my-services.html.twig', [
            'services' => $myServices,
        ]);
    }

    /**
     * @Route("service/pot-matches/{id}", name="service_pot_matches", defaults={"id"=null}))
     */
    public function listPotMatches($id, Request $request)
    {
        $serviceId = null;
        $pagination = null;
        $potMatchesComplete = null;
        $currentService = null;
        $myServices = $this->serviceLoader->loadByUser($this->getUser()->getId());

        if ($myServices !== []){
            $serviceId = $id === null? $myServices[0]->getId(): $id;
            $currentService = $this->serviceLoader->getService($serviceId);
            $paginationQuery = $this->serviceLoader->getPotMatchesByServiceIdQuery($serviceId);
            $pagination = $this->paginator->paginate(
                $paginationQuery,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 8)
            );
            $potMatchesArray = $pagination->getItems();
            $potMatchesComplete = $this->serviceLoader->addDistanceAndRating($currentService, $potMatchesArray);
        }

        return $this->render('service/pot-matches.html.twig', [
            'myServices' => $myServices,
            'service' => $currentService,
            'pagination' =>$pagination,
            'potMatches' => $potMatchesComplete,
        ]);
    }
}
