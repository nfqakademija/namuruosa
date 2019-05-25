<?php


namespace App\Controller\Admin;


use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JobsController extends AbstractController
{
    public function getAllJobs()
    {
        $jobs = $this->getDoctrine()
            ->getRepository(Job::class)
            ->getAllJobs();

        return $this->render('admin/jobs.html.twig', [
            'jobs' => $jobs,
        ]);
    }
}