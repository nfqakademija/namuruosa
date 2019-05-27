<?php

namespace App\Controller;

use App\Entity\Job;
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

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/job/edit/{id}", name="jobEdit")
     */
    public function editJob( Request $request, int $id, Loader $loader)
    {
        $job = $loader->getJob($id);
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
            'jobs' => $myJobs,
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
            'potMatchesByJobs' => $myMatchingServices,
        ]);
    }
}
