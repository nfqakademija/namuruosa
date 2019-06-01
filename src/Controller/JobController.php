<?php

namespace App\Controller;

use App\Form\JobType;
use App\Job\Loader;
use App\Job\Validator;
use App\Match\Loader as MatchLoader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{

    private $em;
    private $loader;
    private $matchLoader;
    private $validator;

    /**
     * JobController constructor.
     * @param EntityManagerInterface $em
     * @param Loader $loader
     * @param matchLoader $matchLoader
     */
    public function __construct(EntityManagerInterface $em, Loader $loader, MatchLoader $matchLoader, Validator $validator)
    {
        $this->em = $em;
        $this->loader = $loader;
        $this->matchLoader = $matchLoader;
        $this->validator = $validator;
    }

    /**
     * @Route("/job/add", name="jobAdd")
     */
    public function addJob(Request $request)
    {
        $form = $this->createForm(JobType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $job = $form->getData();
            $job->setUserId($this->getUser());
            $this->em->persist($job);
            $this->em->flush();
            return $this->redirectToRoute('my_jobs');
        }

        return $this->render('job/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/job/edit/{id}", name="jobEdit")
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editJob(Request $request, int $id)
    {
        $job = $this->loader->getJob($id);
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('my_jobs');
        }

        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $job->getId(),
        ]);
    }

    /**
     * @Route("/job/delete/{jobId}", name="jobDelete")
     */
    public function deleteJob($jobId, Request $request)
    {
        $userId = $this->getUser()->getId();
        $deleteRequestValid = $this->validator->checkDeleteValidity($jobId, $userId);
        if($deleteRequestValid['validity']){
            $this->loader->delete($jobId);
            $this->addFlash("success", $deleteRequestValid['message']);
        } else{
            $this->addFlash("danger", $deleteRequestValid['message']);
        }

        return $this->redirectToRoute('my_jobs');
    }

    /**
     *
     * @Route("job/myjobs", name="my_jobs")
     */
    public function listMyJobs()
    {
        $userId = $this->getUser()->getId();
        $myJobs = $this->loader->loadByUser($userId);

        return $this->render('job/my-jobs.html.twig', [
            'jobs' => $myJobs,
        ]);
    }

    /**
     * @Route("job/pot-matches", name="job_pot_matches")
     */
    public function listPotMatches()
    {
        $userId = $this->getUser()->getId();
        $myMatchingServices = $this->loader->loadPotMatches($userId);

        return $this->render('job/pot-matches.html.twig', [
            'potMatchesByJobs' => $myMatchingServices,
        ]);
    }
}
