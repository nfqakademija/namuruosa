<?php

namespace App\Controller;

use App\Form\JobType;
use App\Job\Loader;
use App\Job\Validator;
use App\Match\Loader as MatchLoader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{

    private $em;
    private $loader;
    private $matchLoader;
    private $validator;
    private $paginator;

    /**
     * JobController constructor.
     * @param EntityManagerInterface $em
     * @param Loader $loader
     * @param matchLoader $matchLoader
     */
    public function __construct(EntityManagerInterface $em, Loader $loader, MatchLoader $matchLoader, Validator $validator, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->loader = $loader;
        $this->matchLoader = $matchLoader;
        $this->validator = $validator;
        $this->paginator = $paginator;
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
        $userId = $this->getUser()->getId();
        $job = $this->loader->getJob($id);
        $editRequestValid = $this->validator->checkEditValidity($id, $userId);
        if ($editRequestValid['validity']) {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        } else {
            $this->addFlash("danger", $editRequestValid['message']);
            return $this->redirectToRoute('my_jobs');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash("success", $editRequestValid['message']);
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
    public function listMyJobs(Request $request)
    {
        $userId = $this->getUser()->getId();
        $myJobsQuery = $this->loader->loadQueryByUser($userId);
        $myJobs = $this->paginator->paginate(
            $myJobsQuery,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('job/my-jobs.html.twig', [
            'jobs' => $myJobs,
        ]);
    }

    /**
     * @Route("job/pot-matches/{id}", name="job_pot_matches", defaults={"id"=null}))
     */
    public function listPotMatches($id, Request $request)
    {
        $jobId = null;
        $pagination = null;
        $potMatchesComplete = null;
        $currentJob = null;
        $myJobs = $this->loader->loadByUser($this->getUser()->getId());

        if ($myJobs !== []){
            $jobId = $id === null? $myJobs[0]->getId(): $id;
            $currentJob = $this->loader->getJob($jobId);
            $paginationQuery = $this->loader->getPotMatchesByJobIdQuery($jobId);
            $pagination = $this->paginator->paginate(
                $paginationQuery,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 8)
            );
            $potMatchesArray = $pagination->getItems();
            $potMatchesComplete = $this->loader->addDistanceAndRating($currentJob, $potMatchesArray);
        }

        return $this->render('job/pot-matches.html.twig', [
            'myJobs' => $myJobs,
            'job' => $currentJob,
            'pagination' =>$pagination,
            'potMatches' => $potMatchesComplete,
        ]);
    }
}
