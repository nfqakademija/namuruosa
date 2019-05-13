<?php

namespace App\Controller;

use App\Form\JobType;
use App\Job\Loader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{

    /**
     * @Route("/job/add", name="jobAdd")
     */
    public function addJob(Request $request)
    {
        $form = $this->createForm(JobType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $job = $form->getData();
            $job->setUserId($this->getUser());
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('my_jobs');
        }

        return $this->render('job/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/job/delete/{jobId}", name="jobDelete")
     */
    public function deleteJob($jobId, Request $request, Loader $loader)
    {
        $loader->delete($jobId);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);

    }

    /**
     *
     * @Route("job/myjobs", name="my_jobs")
     */
    public function listMyJobs(Loader $loader)
    {
        $userId = $this->getUser()->getId();
        $myJobs = $loader->loadByUser($userId);

        return $this->render('job/my-jobs.html.twig', [
            'jobsArray'=>[$myJobs],
        ]);
    }

    /**
     * @Route("job/pot-matches", name="job_pot_matches")
     */
    public function listPotMatches(Loader $loader)
    {
        $userId = $this->getUser()->getId();
        $myMatchingServices = $loader->loadPotMatches($userId);

        return $this->render('job/pot-matches.html.twig', [
            'potMatchesByJobs'=>$myMatchingServices,
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
