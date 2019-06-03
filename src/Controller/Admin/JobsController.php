<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function adminDeleteJob($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em
            ->getRepository(Job::class)
            ->find($id);

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_jobs');
    }
}
