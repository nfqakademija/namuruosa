<?php


namespace App\Controller\Admin;


use App\Entity\Reports;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReportsController extends AbstractController
{
    public function homeReports()
    {
        return $this->redirectToRoute('admin_reports_new');
    }

    public function getNewReports()
    {
        $reports = $this->getDoctrine()
            ->getRepository(Reports::class)
            ->getNewReports();

        return $this->render('admin/reports.html.twig', [
            'reports' => $reports,
        ]);
    }

    public function getSolvedReports()
    {
        $reports = $this->getDoctrine()
            ->getRepository(Reports::class)
            ->getSolvedReports();

        return $this->render('admin/reports.html.twig', [
            'reports' => $reports,
        ]);
    }

    public function solveReport($id)
    {
        $this->getDoctrine()
            ->getRepository(Reports::class)
            ->solveReport($id);

        return $this->redirectToRoute('admin_reports_new');
    }
}