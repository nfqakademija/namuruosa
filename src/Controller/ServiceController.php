<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 11.31
 */

namespace App\Controller;


use App\Form\ServiceType;
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
        $services = $this->getDoctrine()->getRepository('App:Service')->getByUserId($userId);
        return $this->render('service/list.html.twig', [
            'services'=>$services,
    ]);
    }
}