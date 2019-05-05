<?php

namespace App\Controller;

use App\Form\JobType;
use App\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{

    /**
     * @Route("/job/add", name="jobAdd")
     */
    public function addService(Request $request)
    {
        $form = $this->createForm(JobType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $service = $form->getData();
            $service->setUserId($this->getUser());
            $em->persist($service);
            $em->flush();
            return $this->redirectToRoute('listMatches');
        }

        return $this->render('job/add.html.twig', [
            'serviceForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/job", name="job")
     */
    public function index()
    {

        $latitudeFrom = 54.961905;
        $longitudeFrom = 23.948033;
        $latitudeTo = 54.771107;
        $longitudeTo = 23.884170;

        function haversineGreatCircleDistance(
            $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
        {
            // convert from degrees to radians
            $latFrom = deg2rad($latitudeFrom);
            $lonFrom = deg2rad($longitudeFrom);
            $latTo = deg2rad($latitudeTo);
            $lonTo = deg2rad($longitudeTo);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            return $angle * $earthRadius;
        }

        $dist = haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000);
        $dist = $dist/1000;



        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
            'dist' => $dist,
        ]);
    }
}
